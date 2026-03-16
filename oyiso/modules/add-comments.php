<?php
if (!defined('ABSPATH')) {
  exit;
}

add_action('wp_ajax_reply_comment', 'reply_comment');
add_action('wp_ajax_nopriv_reply_comment', 'reply_comment');

function reply_comment() {
  // nonceを検証
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'oyiso_comment_nonce')) {
    wp_die('security_error');
  }

  if (get_option('oyiso_comments_login') && !is_user_logged_in()) {
    wp_die('no_login');
  }

  if (
    !empty($_POST['comment_post_ID']) && 
    !empty($_POST['comment']) && 
    !empty($_POST['author']) && 
    !empty($_POST['email'])
  ) 
  {
    // コメントが有効かどうかを判定
    $post_id = intval($_POST['comment_post_ID']);
    $post_type = get_post_type($post_id);
    $post_type_options = [
      'page' => 'oyiso_comments_page',
      'post' => 'oyiso_comments_post',
      'moment' => 'oyiso_comments_moment'
    ];
    if (array_key_exists($post_type, $post_type_options)) {
      $option_name = $post_type_options[$post_type];
      if (!get_option($option_name) || !comments_open($post_id)) {
        wp_die('closed');
      }
    } else if (!comments_open($post_id)) {
      wp_die('closed');
    }

    // ニックネームをチェック
    if (isset($_POST['author'])) {
      (string)$author = $_POST['author'];
      $author = preg_replace('/ +/', ' ', trim($author));
      $author_count = mb_strlen($author);
      if ($author_count < 1) {
        wp_die('short_author');
      }
    } else {
      wp_die('no_author');
    }

    // メールをチェック
    if (!isset($_POST['email'])) {
      wp_die('no_email');
    }

    // コメント文字数をチェック
    if (isset($_POST['comment'])) {
      (string)$text = $_POST['comment'];
      $text = preg_replace('/ +/', ' ', trim($text));
      $text_count = mb_strlen($text);
      if ($text_count < 1) {
        wp_die('short_comment');
      }
    } else {
      wp_die('no_comment');
    }

    // ユーザーURLのhttpヘッダーを修正
    if (isset($_POST['url'])) {
      $user_url = $_POST['url'];
      if ($user_url) {
        if (strpos($user_url, 'http://') === false && strpos($user_url, 'https://') === false) {
          $user_url = 'http://'.$user_url;
        }
      }
    } else {
      $user_url = '';
    }

    // ユーザーがログインしているかチェック
    if (is_user_logged_in()) {
      $user_id = get_current_user_id();

      // ブロガーかどうかをチェック
      $master = '
      <span class="tag master">
        <svg class="icon" aria-hidden="true">
          <use xlink:href="#icon-ghost-smile-fill"></use>
        </svg>
        <span>管理人</span>
      </span>
      ';
      $user_avatar = get_avatar_url($user_id);
    } else {

      // ゲストコメントを許可するかチェック
      if (get_option('oyiso_comments_login')) {
        wp_die('no_login');
      }
      $user_id = 0;
      $master = '';
      
      // QQメールかどうかを検出
      $user_email = sanitize_email($_POST['email']);
      if (preg_match('/^\d+@qq\.com$/', $user_email)) {
        $email_parts = explode('@', $user_email);
        $user_avatar = 'https://q1.qlogo.cn/g?b=qq&nk=' . $email_parts[0] . '&s=640';
      } else {
        $user_avatar = get_avatar_url($user_email);
      }
    }

    $user_ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''); // コメント者IPを取得

    // 親コメントIDを検証
    $comment_parent_id = isset($_POST['comment_parent']) ? intval($_POST['comment_parent']) : 0;

    $comment_data = array(
      'comment_post_ID' => $post_id,
      'comment_author' => sanitize_text_field($author),
      'comment_author_email' => sanitize_email($_POST['email']),
      'comment_author_url' => esc_url_raw($user_url),
      'comment_author_IP' => sanitize_text_field($user_ip),
      'comment_date' => current_time('mysql'),
      'comment_content' => sanitize_textarea_field($text),
      'comment_parent' => $comment_parent_id,
      'user_id' => $user_id,
    );

    // 審査不要フラグ
    $approved_comment = false;

    // コメントが既に存在するかチェック
    $existing_comments = get_comments(array(
      'author_email' => $comment_data['comment_author_email'],
      'post_id' => $comment_data['comment_post_ID'],
    ));
    $existing_comments = array_filter($existing_comments, function($comment) use ($comment_data) {
      return trim($comment -> comment_content) === trim($comment_data['comment_content']);
    });
    if (count($existing_comments) > 0) {
      wp_die('exist');
    } else {
      $comment_id = wp_new_comment($comment_data);
      $approved = get_comment($comment_id) -> comment_approved;
      if ($approved == '0') {
        $approved = ' - 審査待ち';
      } else {
        $approved = '';
        $approved_comment = true;
      }
    }

    // ユーザーIP所属地を取得して保存
    $location = get_user_location($user_ip);
    if ($location && $location != '不明' && $location != 'Unknown') {
      add_comment_meta($comment_id, 'location', $location);
      $location = '
        <span class="tag location">
          <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-map-pin-fill"></use>
          </svg>
          <span>'.$location.'</span>
        </span>
      ';
    } else {
      $status = $location == 'Unknown' ? 'Unknown' : '不明';
      $location = '
        <span class="tag location">
          <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-map-pin-fill"></use>
          </svg>
          <span>'.$status.'</span>
        </span>
      ';
    }

    if (!get_option('oyiso_comments_ip')) { // IPを表示しない
      $location = '';
    }
    

    // コメント時刻を取得
    $user_timezone = sanitize_text_field($_POST['timezone'] ?? 'Asia/Tokyo');
    // タイムゾーンが有効か検証
    if (!in_array($user_timezone, timezone_identifiers_list(), true)) {
      $user_timezone = 'Asia/Tokyo';
    }
    $current_time = time();
    $comment_date = wp_date('Y年n月j日', $current_time, new DateTimeZone($user_timezone));
    $comment_time = wp_date('H:i', $current_time, new DateTimeZone($user_timezone));


    // 親ニックネームを取得
    $parent_comment_id = $comment_data['comment_parent'];
    if (!empty($parent_comment_id)) {
      $parent_comment = get_comment($parent_comment_id);
      $user_id = $parent_comment -> user_id;
  
      if ($user_id > 0) {
        $comment_parent_author = get_userdata($user_id) -> display_name;
      } else if ($user_id == null) {
        $comment_parent_author = 'このメッセージは削除されました';
      } else {
        $comment_parent_author = $parent_comment -> comment_author;
      }
      $comment_parent = '<span class="at">@'.$comment_parent_author.' </span>';

    } else {
      $comment_parent = '';
    }

    if ($user_url) {
      $user_url = "href='$user_url'";
    } else {
      $user_url = '';
    }
    
    $comment_list =  '
    <li class="own" id="'.$comment_id.'">
      <div class="datetime">'.$comment_date.'</div>
      <div class="box">
        <div class="avatar">
          <img src="'.$user_avatar.'" alt="">
          <div class="at-user" title="返信: '.$author.'">
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-reply-fill"></use>
            </svg>
          </div>
        </div>
        <div class="reply-info">
          <div class="reply-user">
            <a '.$user_url.' class="name" target="_blank">'.$author.'</a>
          '.$master.$location.'
          </div>
          <div class="reply-content">
            <div class="content">
              <div class="text">'.$comment_parent.'<p>'.stripslashes($text).'</p><span class="date">'.$comment_time.$approved.'</span></div>
            </div>
          </div>
        </div>
      </div>
    </li>';

    if ($user_id != 0) {
      $back_args = array(
        'approved' => $approved_comment,
        'comment' => $comment_list,
      );
    } else {
      $back_args = array(
        'approved' => $approved_comment,
        'comment' => $comment_list,
        'avatar' => $user_avatar
      );
    }
    echo json_encode($back_args);
    
  } else {
    echo 'error';
  }
  wp_die();
}


// コメントフラッドチェックの時間間隔を変更
function comment_flood_wait_time($flood_die, $time_lastcomment, $time_newcomment) {
  if ($time_lastcomment && $time_newcomment) {
      $flood_die = ($time_newcomment - $time_lastcomment) < 10;
  }
  return $flood_die;
}
add_filter('comment_flood_filter', 'comment_flood_wait_time', 10, 3);

function custom_comment_flood_message( $msg ) {
  return 'flood';
}
add_filter( 'comment_flood_message', 'custom_comment_flood_message' );