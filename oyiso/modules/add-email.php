<?php
if (!defined('ABSPATH')) {
  exit;
}

// メールSMTP
use PHPMailer\PHPMailer\PHPMailer;
require_once get_template_directory().'/vendor/autoload.php';
if (get_option('oyiso_smtp')) {
  add_action('phpmailer_init', 'custom_phpmailer_init');
  function custom_phpmailer_init(PHPMailer $phpmailer) {
    $host = get_option('oyiso_smtp_host');
    $port = get_option('oyiso_smtp_port');
    $username = get_option('oyiso_smtp_username');
    $password = get_option('oyiso_smtp_password');
    $secure = get_option('oyiso_smtp_secure');
    $smtp_fromname = get_option('oyiso_smtp_fromname');
    $display_name = get_userdata(1)->display_name;
    $fromname = $smtp_fromname ? $smtp_fromname : ($display_name ? $display_name : get_bloginfo('name'));
    if ($host && $port && $username && $password && $secure) {
      $phpmailer->isSMTP();     
      $phpmailer->Host = $host; // SMTPサーバーアドレス
      $phpmailer->SMTPAuth = true; 
      $phpmailer->Port = $port; 
      $phpmailer->Username = $username; // SMTPユーザー名、通常はメールアドレス
      $phpmailer->Password = $password; // SMTPパスワード
      $phpmailer->SMTPSecure = $secure; // 暗号化方式、'ssl' または 'tls'
      $phpmailer->From = $username; // 送信者アドレス
      $phpmailer->FromName = $fromname; // 送信者名
    }
  }
}


// コメント通知（コメント、返信）
add_action('comment_post', 'oyiso_comment_post');
function oyiso_comment_post($comment_id) {
  $comment = get_comment($comment_id); // コメントオブジェクト
  $comment_content = $comment->comment_content; // コメント内容
  $parent_comment_id = $comment->comment_parent; // 親コメントID
  $comment_status = wp_get_comment_status($comment_id); // コメントステータス
  $comment_author = $comment->comment_author; // コメント者ニックネーム
  $comment_author_email = $comment->comment_author_email; // コメント者メール
  $post_id = $comment->comment_post_ID; // 記事ID
  $post_name = get_the_title($post_id); // 記事タイトル
  $post_url = get_permalink($post_id); // 記事リンク
  $post_type = get_post_type($post_id); // 記事タイプ
  $smtp_sitename = get_option('oyiso_smtp_sitename'); // カスタムサイト名
  $site_name = $smtp_sitename ? $smtp_sitename : get_bloginfo('name'); // サイト名
  $site_url = get_bloginfo('url'); // サイトリンク
  $admin_email = get_option('admin_email'); // ブロガーメール

  $theme_colors = get_option('oyiso_theme_colors');
  if (isset($theme_colors->pri) && !empty($theme_colors->pri)) {
    $theme_color_pri = $theme_colors->pri;
  } else {
    $theme_color_pri = '#8183ff';
  }

  $email_css = "style='background-color:#f5f5f5;padding:15px;border-left:3px solid {$theme_color_pri};'"; // メールスタイル
  $headers = array('Content-Type: text/html; charset=UTF-8'); // メールヘッダー

  // 返信コメントの場合
  if (!empty($parent_comment_id)) {
    $parent_comment = get_comment($parent_comment_id); // 親コメントオブジェクト
    $parent_comment_author = $parent_comment->comment_author; // 返信先ニックネーム
    $parent_comment_author_email = $parent_comment->comment_author_email; // 返信先メール


    // 審査待ちはブロガーに送信
    if ($comment_status != 'approved') {
      $comment_approve_url = admin_url("comment.php?action=editcomment&c={$comment_id}");
      $pending = "<p>承認URL：<a href='{$comment_approve_url}'>{$comment_approve_url}</a></p>";
      $subject = "サイト [{$site_name}] に新しいコメントがあります（審査待ち）";
      if ($parent_comment_author_email == $admin_email) {
        $who = 'あなた';
      } else {
        $who = $parent_comment_author;
      }
      if ($post_type == 'post' || $post_type == 'page') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>{$who}</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
          {$pending}
        ";
      } else if ($post_type == 'moment') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が瞬間で <b>{$who}</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          {$pending}
        ";
      } else {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>{$who}</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          {$pending}
        ";
      }
      wp_mail($admin_email, $subject, $message, $headers);
      return;
    }

    // 送信者がブロガーで、返信先がブロガーでない場合
    if ($comment_author_email == $admin_email) {
      if ($parent_comment_author_email != $admin_email) {
        // 返信先に送信（ブロガーがxxxに返信）
        $subject = "サイト [{$site_name}] のコメントに新しい返信があります";
        if ($post_type == 'post' || $post_type == 'page') {
          $message = "
            <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
            <p>ブロガー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
            <p {$email_css}>{$comment_content}</p>
            <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
          ";
        } else if ($post_type == 'moment') {
          $message = "
            <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
            <p>ブロガー <b>{$comment_author}</b> が瞬間で <b>あなた</b> に返信しました：</p>
            <p {$email_css}>{$comment_content}</p>
            <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          ";
        } else {
          $message = "
            <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
            <p>ブロガー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
            <p {$email_css}>{$comment_content}</p>
            <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          ";
        }
        wp_mail($parent_comment_author_email, $subject, $message, $headers);
        return;
      } else {
        return;
      }
    } 

    // 送信者がブロガーでなく、返信先がブロガーの場合
    if ($comment_author_email != $admin_email && $parent_comment_author_email == $admin_email) {
      // ブロガーに送信 審査必要
      // 審査待ち状態の場合
      if ($comment_status != 'approved') {
        $comment_approve_url = admin_url("comment.php?action=editcomment&c={$comment_id}");
        $pending = "<p>承認URL：<a href='{$comment_approve_url}'>{$comment_approve_url}</a></p>";
        $subject = "サイト [{$site_name}] に新しいコメントがあります（審査待ち）";
      } else {
        $pending = '';
        $subject = "サイト [{$site_name}] に新しいコメントがあります";
      }

      // ブロガーに送信
      if ($post_type == 'post' || $post_type == 'page') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
          {$pending}
        ";
      } else if ($post_type == 'moment') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が瞬間で <b>あなた</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          {$pending}
        ";
      } else {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          {$pending}
        ";
      }
      wp_mail($admin_email, $subject, $message, $headers);
      return;
    }

    // 送信者がブロガーでなく、返信先もブロガーでない場合
    if ($comment_author_email != $admin_email && $parent_comment_author_email != $admin_email) {
      // 返信先とブロガーに送信（xxxがxxxに返信）
      // 返信先に送信
      $subject = "サイト [{$site_name}] のコメントに新しい返信があります";
      if ($post_type == 'post' || $post_type == 'page') {
        $message = "
          <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
        ";
      } else if ($post_type == 'moment') {
        $message = "
          <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
          <p>ユーザー <b>{$comment_author}</b> が瞬間で <b>あなた</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
        ";
      } else {
        $message = "
          <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
        ";
      }
      wp_mail($parent_comment_author_email, $subject, $message, $headers);

      // ブロガーに送信
      $subject = "サイト [{$site_name}] に新しいコメント返信があります";
      if ($post_type == 'post' || $post_type == 'page') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>{$parent_comment_author}</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
        ";
      } else if ($post_type == 'moment') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が瞬間で <b>{$parent_comment_author}</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
        ";
      } else {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> で <b>{$parent_comment_author}</b> に返信しました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
        ";
      }
      wp_mail($admin_email, $subject, $message, $headers);
      return;
    }

  } else {
    // ブロガーのコメントの場合、メールを送信しない
    if ($comment_author_email == $admin_email) {
      return;
    }

    // コメント者がブロガーでない場合、ブロガーにメールを送信
    if($comment_author_email != $admin_email) {
      // 審査待ち状態の場合
      if ($comment_status != 'approved') {
        $comment_approve_url = admin_url("comment.php?action=editcomment&c={$comment_id}");
        $pending = "<p>承認URL：<a href='{$comment_approve_url}'>{$comment_approve_url}</a></p>";
        $subject = "サイト [{$site_name}] に新しいコメントがあります（審査待ち）";
      } else {
        $pending = '';
        $subject = "サイト [{$site_name}] に新しいコメントがあります";
      }

      // ブロガーに送信
      if ($post_type == 'post' || $post_type == 'page') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> にコメントしました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
          {$pending}
        ";
      } else if ($post_type == 'moment') {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が瞬間にコメントしました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          {$pending}
        ";
      } else {
        $message = "
          <p>ユーザー <b>{$comment_author}</b> が記事 <b>{$post_name}</b> にコメントしました：</p>
          <p {$email_css}>{$comment_content}</p>
          <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
          {$pending}
        ";
      }
      wp_mail($admin_email, $subject, $message, $headers);
      return;
    }
  }
}


// 承認通知
add_action('wp_set_comment_status', 'oyiso_comment_status_change', 10, 2);
function oyiso_comment_status_change($comment_id, $comment_status) {

  // ゴミ箱へ移動またはスパムコメントとしてマークされた場合はメールを送信しない
  if (in_array($comment_status, ['trash', 'spam', 0, 1])) {
    return;
  }
  
  $comment = get_comment($comment_id); // コメントオブジェクト
  // コメントオブジェクトが存在するかチェック
  if (!$comment) {
    return;
  }
  $comment_author_email = $comment->comment_author_email; // コメント者メール
  $parent_comment_id = $comment->comment_parent; // 親コメントID
  $admin_email = get_option('admin_email'); // ブロガーメール

  $theme_colors = get_option('oyiso_theme_colors');
  if (isset($theme_colors->pri) && !empty($theme_colors->pri)) {
    $theme_color_pri = $theme_colors->pri;
  } else {
    $theme_color_pri = '#8183ff';
  }

  $email_css = "style='background-color:#f5f5f5;padding:15px;border-left:3px solid {$theme_color_pri};'"; // メールスタイル
  $headers = array('Content-Type: text/html; charset=UTF-8'); // メールヘッダー


  // 返信者に通知 -承認
  function reply($comment_id, $email_css, $headers, $success = true) {
    $comment = get_comment($comment_id); // コメントオブジェクト
    $comment_content = $comment->comment_content; // コメント内容
    $comment_author = $comment->comment_author; // コメント者ニックネーム
    $comment_author_email = $comment->comment_author_email; // コメント者メール
    $smtp_sitename = get_option('oyiso_smtp_sitename'); // カスタムサイト名
    $site_name = $smtp_sitename ? $smtp_sitename : get_bloginfo('name'); // サイト名
    $site_url = get_bloginfo('url'); // サイトリンク
    $post_id = $comment->comment_post_ID; // 記事ID
    $post_name = get_the_title($post_id); // 記事タイトル
    $post_url = get_permalink($post_id); // 記事リンク
    $post_type = get_post_type($post_id); // 記事タイプ
    
    if ($success) {
      $status = 'が承認されました';
    } else {
      $status = 'が却下されました';
    }
    $subject = "サイト [{$site_name}] で投稿したコメント{$status}！";
    if ($post_type == 'post' || $post_type == 'page') {
      if ($success) {
        $url = "<p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>";
      } else {
        $url = "<p>記事URL：<a href='{$post_url}'>{$post_url}</a></p>";
      }
      $message = "
        <p><b>{$comment_author}</b> さん、こんにちは！</p>
        <p>記事 <b>{$post_name}</b> で投稿したコメント{$status}！</p>
        <p>コメント内容：</p>
        <p {$email_css}>{$comment_content}</p>
        {$url}
      ";
    } else if ($post_type == 'moment') {
      $message = "
        <p><b>{$comment_author}</b> さん、こんにちは！</p>
        <p>瞬間で投稿したコメント{$status}！</p>
        <p>コメント内容：</p>
        <p {$email_css}>{$comment_content}</p>
        <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
      ";
    } else {
      $message = "
        <p><b>{$comment_author}</b> さん、こんにちは！</p>
        <p>記事 <b>{$post_name}</b> で投稿したコメント{$status}！</p>
        <p>コメント内容：</p>
        <p {$email_css}>{$comment_content}</p>
        <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
      ";
    }
    wp_mail($comment_author_email, $subject, $message, $headers);
  }


  // 返信先に通知
  function reply_to($comment_id, $email_css, $headers, $parent_comment, $admin_email) {
    $comment = get_comment($comment_id); // コメントオブジェクト
    $comment_content = $comment->comment_content; // コメント内容
    $comment_author = $comment->comment_author; // コメント者ニックネーム
    $comment_author_email = $comment->comment_author_email; // コメント者メール
    $smtp_sitename = get_option('oyiso_smtp_sitename'); // カスタムサイト名
    $site_name = $smtp_sitename ? $smtp_sitename : get_bloginfo('name'); // サイト名
    $site_url = get_bloginfo('url'); // サイトリンク
    $post_id = $comment->comment_post_ID; // 記事ID
    $post_name = get_the_title($post_id); // 記事タイトル
    $post_url = get_permalink($post_id); // 記事リンク
    $post_type = get_post_type($post_id); // 記事タイプ
    $parent_comment_author = $parent_comment->comment_author; // 返信先ニックネーム
    $parent_comment_author_email = $parent_comment->comment_author_email; // 返信先メール
    
    if ($comment_author_email == $admin_email) {
      $reply_author = "ブロガー <b>{$comment_author}</b>";
    } else {
      $reply_author = "ユーザー <b>{$comment_author}</b>";
    }
    $subject = "サイト [{$site_name}] のコメントに新しい返信があります！";
    if ($post_type == 'post' || $post_type == 'page') {
      $message = "
        <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
        <p>{$reply_author} が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
        <p {$email_css}>{$comment_content}</p>
        <p>記事URL：<a href='{$post_url}#comment_reply={$comment_id}'>{$post_url}#comment_reply={$comment_id}</a></p>
      ";
    } else if ($post_type == 'moment') {
      $message = "
        <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
        <p>{$reply_author} が瞬間で <b>あなた</b> に返信しました：</p>
        <p {$email_css}>{$comment_content}</p>
        <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
      ";
    } else {
      $message = "
        <p><b>{$parent_comment_author}</b> さん、こんにちは！</p>
        <p>{$reply_author} が記事 <b>{$post_name}</b> で <b>あなた</b> に返信しました：</p>
        <p {$email_css}>{$comment_content}</p>
        <p>ブログURL：<a href='{$site_url}'>{$site_url}</a></p>
      ";
    }
    wp_mail($parent_comment_author_email, $subject, $message, $headers);
  }

  // 返信コメントの場合
  if (!empty($parent_comment_id)) {
    $parent_comment = get_comment($parent_comment_id); // 親コメントオブジェクト
    $parent_comment_author = $parent_comment->comment_author; // 返信先ニックネーム
    $parent_comment_author_email = $parent_comment->comment_author_email; // 返信先メール

    // 返信者と返信先が共にブロガー本人の場合、メールを送信しない
    if ($parent_comment_author_email == $comment_author_email && $comment_author_email == $admin_email) {
      return;
    }

    // 返信者と返信先が同一人物で、ブロガーでない場合、メールを送信
    if ($parent_comment_author_email == $comment_author_email && $comment_author_email != $admin_email) {
      if ($comment_status == 'approve') {
        reply($comment_id, $email_css, $headers);
      } else {
        reply($comment_id, $email_css, $headers, false);
      }
      return;
    }

    // 返信者と返信先が異なる人物で、返信者がブロガー、返信先が他者の場合
    if ($parent_comment_author_email != $comment_author_email && $comment_author_email == $admin_email && $parent_comment_author_email != $admin_email ) {
      if ($comment_status == 'approve') {
        reply_to($comment_id, $email_css,$headers, $parent_comment, $admin_email);
      }
      return;
    }
    
    // 返信者と返信先が異なる人物で、返信者が他者、返信先がブロガーの場合
    if ($parent_comment_author_email != $comment_author_email && $comment_author_email != $admin_email && $parent_comment_author_email == $admin_email) {
      if ($comment_status == 'approve') {
        reply($comment_id, $email_css, $headers);
      } else {
        reply($comment_id, $email_css, $headers, false);
      }
      return;
    }

    if ($parent_comment_author_email != $comment_author_email && $comment_author_email != $admin_email && $parent_comment_author_email != $admin_email) {
      if ($comment_status == 'approve') {
        reply($comment_id, $email_css, $headers);
        reply_to($comment_id, $email_css,$headers, $parent_comment, $admin_email);
      } else {
        reply($comment_id, $email_css, $headers, false);
      }
      return;
    }

  } else {
    // 返信者がブロガー本人の場合、メールを送信しない
    if ($comment_author_email == $admin_email) {
      return;
    }

    // 返信者がブロガーでない場合、メールを送信
    if ($comment_status == 'approve') {
      reply($comment_id, $email_css, $headers);
    } else {
      reply($comment_id, $email_css, $headers, false);
    }
    return;
  }
}


// コメント通知を無効化
add_filter('comments_notify', '__return_false');

// コメント審査待ち通知を無効化
add_filter('moderation_notify', '__return_false');
