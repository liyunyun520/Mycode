<?php
if (!defined('ABSPATH')) {
  exit;
}

// コメントエリア
add_action('wp_ajax_nopriv_load_comments', 'load_comments');
add_action('wp_ajax_load_comments', 'load_comments');

function load_comments() {
  // 入力検証
  $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
  $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
  $query = isset($_POST['query']) ? intval($_POST['query']) : 1;
  $comments_per_page = isset($_POST['comments_per_page']) ? intval($_POST['comments_per_page']) : 10;
  $visitor_author = isset($_POST['visitor_author']) ? sanitize_text_field($_POST['visitor_author']) : '';
  $visitor_email = isset($_POST['visitor_email']) ? sanitize_email($_POST['visitor_email']) : '';
  $user_timezone = isset($_POST['timezone']) ? sanitize_text_field($_POST['timezone']) : 'Asia/Tokyo';
  
  // タイムゾーンが有効か検証
  static $valid_timezones = null;
  if ($valid_timezones === null) {
    $valid_timezones = timezone_identifiers_list();
  }
  if (!in_array($user_timezone, $valid_timezones)) {
    $user_timezone = 'Asia/Tokyo';
  }
  
  // 記事でコメントが閉じている場合
  $post = get_post($post_id);
  if (!$post) {
    wp_send_json_error('invalid_post');
  }
  if ($post->comment_status != 'open') {
    wp_send_json_error('closed');
  }

  // 承認済みコメント数を取得（WordPress内蔵関数を使用）
  $approved_count = (int) get_comments(array(
    'post_id' => $post_id,
    'status' => 'approve',
    'count' => true,
  ));

  $query_count = (int) floor($approved_count / $comments_per_page);
  $residual_count = $approved_count % $comments_per_page;

  // 通常リクエスト（静的変数で最適化）
  $approve_count = 0;
  $comments_to_display = array();
  $ended = false;
  
  // インライン最適化：関数呼び出しオーバーヘッドを回避
  if ($residual_count != 0 && $query_count >= 1) {
    if ($query <= $query_count) {
      // 通常リクエストロジック
      while ($approve_count < $comments_per_page) {
        $comments = get_comments(array(
          'post_id' => $post_id,
          'status' => array('approve', 'hold'),
          'number' => $comments_per_page,
          'offset' => $offset,
        ));
        
        if (empty($comments)) {
          break;
        }
        
        foreach ($comments as $comment) {
          if ($comment->comment_approved == '1') {
            $approve_count++;
          }
          $comments_to_display[] = $comment;
          $offset++;
          
          if ($approve_count >= $comments_per_page) {
            break 2;
          }
        }
      }
      $query++;
    } else if ($query - $query_count == 1) {
      // 特殊リクエスト：残りのコメントを取得
      $comments_to_display = get_comments(array(
        'post_id' => $post_id,
        'status' => array('approve', 'hold'),
        'number' => $comments_per_page,
        'offset' => $offset,
      ));
      $ended = true;
    }
  } else if ($residual_count == 0 && $query_count >= 1) {
    if ($query < $query_count) {
      while ($approve_count < $comments_per_page) {
        $comments = get_comments(array(
          'post_id' => $post_id,
          'status' => array('approve', 'hold'),
          'number' => $comments_per_page,
          'offset' => $offset,
        ));
        
        if (empty($comments)) {
          break;
        }
        
        foreach ($comments as $comment) {
          if ($comment->comment_approved == '1') {
            $approve_count++;
          }
          $comments_to_display[] = $comment;
          $offset++;
          
          if ($approve_count >= $comments_per_page) {
            break 2;
          }
        }
      }
      $query++;
    } else if ($query == $query_count) {
      $comments_to_display = get_comments(array(
        'post_id' => $post_id,
        'status' => array('approve', 'hold'),
        'number' => $comments_per_page,
        'offset' => $offset,
      ));
      $ended = true;
    }
  } else if ($approved_count < $comments_per_page || $approved_count == 0) {
    $comments_to_display = get_comments(array(
      'post_id' => $post_id,
      'status' => array('approve', 'hold'),
      'number' => $comments_per_page,
      'offset' => $offset,
    ));
    $ended = true;
  }

  // 表示コメント数を計算（最適化版：データベースクエリを削減）
  function get_comments_count(int $post_id, string $visitor_author, string $visitor_email, bool $login = false): int {
    // countパラメータで直接数を取得
    $approved_count = (int) get_comments(array(
      'post_id' => $post_id,
      'status' => 'approve',
      'count' => true,
    ));
  
    // 審査待ちコメントは必要な時だけ取得
    $pending_comments = get_comments(array(
      'post_id' => $post_id,
      'status' => 'hold',
      'fields' => 'ids', // IDのみ取得でメモリ削減
    ));

    if (empty($pending_comments)) {
      return $approved_count;
    }

    // 完全な審査待ちコメントを取得（審査待ちコメントがある場合のみ）
    $pending_comments = get_comments(array(
      'post_id' => $post_id,
      'status' => 'hold'
    ));

    $additional_count = 0;
    $logged_user_id = $login ? get_current_user_id() : 0;
    
    foreach ($pending_comments as $pending) {
      $is_own = ($login && $pending->user_id == $logged_user_id) ||
                ($visitor_author === $pending->comment_author && $visitor_email === $pending->comment_author_email);
      if ($is_own) {
        $additional_count++;
      }
    }
    
    return $approved_count + $additional_count;
  }

  // コメントリスト（最適化版：重複呼び出しを削減）
  $comment_list = '';
  $is_admin = is_user_logged_in() && current_user_can('administrator');
  $logged_user_id = get_current_user_id();
  $show_ip = get_option('oyiso_comments_ip');
  
  foreach ($comments_to_display as $comment) {
    $comment_user_id = (int) $comment->user_id;
    $email = $comment->comment_author_email;
    $comment_content = $comment->comment_content;
    $comment_self = false;
    $approve = '';
    
    // 日時をフォーマット
    $comment_timestamp = strtotime($comment->comment_date);
    $date = wp_date('Y年n月j日', $comment_timestamp, new DateTimeZone($user_timezone));
    $time = wp_date('H:i', $comment_timestamp, new DateTimeZone($user_timezone));

    // ログインユーザーのコメントかどうかを判定
    if ($comment_user_id > 0) {
      $comment_author_avatar = get_avatar_url($comment_user_id);
      $user_data = get_userdata($comment_user_id);
      $comment_author = $user_data ? $user_data->display_name : '';

      $comment_self = ($comment_user_id === $logged_user_id);

      if ($comment->comment_approved == '0') {
        if ($comment_user_id === $logged_user_id || ($visitor_author === $comment->comment_author && $visitor_email === $email)) {
          $approve = ' - 審査待ち';
        } elseif ($is_admin) {
          $approve = ' - <a href="'.get_edit_comment_link($comment->comment_ID).'" target="_blank">審査待ち</a>';
        } else {
          continue;
        }
      }
    } else {
      $comment_self = ($visitor_author === $comment->comment_author && $visitor_email === $email);

      // 審査待ちかどうかを判定
      if ($comment->comment_approved == '0') {
        if ($comment_self) {
          $approve = ' - 審査待ち';
        } elseif ($is_admin) {
          $approve = ' - <a href="'.get_edit_comment_link($comment->comment_ID).'" target="_blank">審査待ち</a>';
        } else {
          continue;
        }
      }

      // QQメールかどうかを判定
      if (preg_match('/^\d+@qq\.com$/', $email)) {
        $email_parts = explode('@', $email);
        $comment_author_avatar = 'https://q1.qlogo.cn/g?b=qq&nk=' . $email_parts[0] . '&s=640';
      } else {
        $comment_author_avatar = get_avatar_url($email);
      }
      $comment_author = $comment->comment_author;
    }

    // 返信かどうかを判定（最適化：空チェック追加）
    $comment_parent = (int) $comment->comment_parent;
    $comment_parent_author = '';
    
    if ($comment_parent > 0) {
      $parent_comment = get_comment($comment_parent);
      
      if ($parent_comment) {
        $parent_user_id = (int) $parent_comment->user_id;
        
        if ($parent_user_id > 0) {
          $parent_user_data = get_userdata($parent_user_id);
          $comment_parent_author = $parent_user_data ? $parent_user_data->display_name : 'このメッセージは削除されました';
        } elseif ($parent_user_id === 0) {
          $comment_parent_author = $parent_comment->comment_author ?: 'このメッセージは削除されました';
        } else {
          $comment_parent_author = 'このメッセージは削除されました';
        }
      }
    }

    // ユーザーIP所属地を取得
    $comment_id = $comment->comment_ID;
    $location = get_comment_meta($comment_id, 'location', true);


    // ユーザーIP所属地を取得（最適化：重複コードを削除）
    $location = get_comment_meta($comment_id, 'location', true);
    $location_html = '';
    
    if ($show_ip) {
      if (empty($location) || $location === '不明' || $location === 'Unknown') {
        $comment_user_ip = $comment->comment_author_IP;
        $location = get_user_location($comment_user_ip);
        
        if ($location && $location !== '不明' && $location !== 'Unknown') {
          add_comment_meta($comment_id, 'location', $location);
        }
      }
      
      $display_location = in_array($location, ['不明', 'Unknown', ''], true) 
        ? ($location === 'Unknown' ? 'Unknown' : '不明') 
        : $location;
      
      $location_html = '<span class="tag location">
        <svg class="icon" aria-hidden="true">
          <use xlink:href="#icon-map-pin-fill"></use>
        </svg>
        <span>' . esc_html($display_location) . '</span>
      </span>';
    }

    // コメントスタイルクラス
    $own = $comment_self ? 'class="own"' : '';
    $at_author = $comment_parent_author ? '<span class="at">@' . esc_html($comment_parent_author) . ' </span>' : '';
    
    // ブロガーかどうかを判定
    $master = ($comment_user_id === 1) ? '<span class="tag master">
      <svg class="icon" aria-hidden="true">
        <use xlink:href="#icon-ghost-smile-fill"></use>
      </svg>
      <span>管理人</span>
    </span>' : '';

    // URLがあるかどうかを判定
    $comment_author_url = !empty($comment->comment_author_url) 
      ? 'href="' . esc_url($comment->comment_author_url) . '"' 
      : '';

    // コメントリスト
    $comment_list .= '
    <li ' . $own . ' id="' . $comment->comment_ID . '" data-timezone="' . esc_attr($user_timezone) . '">
      <div class="datetime">' . esc_html($date) . '</div>
      <div class="box">
        <div class="avatar">
          <img src="' . esc_url($comment_author_avatar) . '" alt="">
          <div class="at-user" title="返信: ' . esc_attr($comment_author) . '">
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-reply-fill"></use>
            </svg>
          </div>
        </div>
        <div class="reply-info">
          <div class="reply-user">
            <a ' . $comment_author_url . ' class="name" target="_blank">' . esc_html($comment_author) . '</a>
            ' . $master . $location_html . '
          </div>
          <div class="reply-content">
            <div class="content">
              <div class="text">' . $at_author . '<p>' . htmlspecialchars_decode($comment_content) . '</p><span class="date">' . esc_html($time . $approve) . '</span></div>
            </div>
          </div>
        </div>
      </div>
    </li>
    ';
  }

  echo json_encode(array(
    'offset' => $offset,
    'comments' => $comment_list,
    'ended' => $ended
  ));

  wp_die();
}