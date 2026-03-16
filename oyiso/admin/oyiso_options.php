<?php

require_once get_template_directory().'/vendor/autoload.php';
use Upyun\Upyun;
use Upyun\Config;

// テーマ設定ajax処理
function oyiso_options() {
  // ユーザー権限を確認
  if (!current_user_can('administrator')) {
    wp_send_json_error('failed');

  } else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // フォームデータを取得・サニタイズ
      // $options = array_map('sanitize_text_field', $_POST);
      $options = $_POST;
      // error_log(print_r($options, true));
      // return;
      $obj_theme_color = new stdClass();

      // テーマカラーを取得
      $theme_color = get_option('oyiso_theme_color');

      // オプションを更新
      foreach ($options as $name => $value) {
        // フック名をクリア
        if ($name == 'action') continue;
        // error_log(print_r($name, true));
        // continue;

        // オプションを処理
        if ($value == 'true' || $value == 'false') {
          $value = $value == 'true' ? true : false;
        }

        update_option($name, $value);

        // テーマカラーを処理
        if ($name == 'oyiso_theme_colors') {
          // error_log($value);
          // error_log(print_r($value, true));
          update_option('oyiso_theme_colors', json_decode(stripslashes($options['oyiso_theme_colors'])));
        }

        // カスタムテーマカラーを処理
        if ($name == 'oyiso_theme_colors_custom') {
          update_option('oyiso_theme_colors_custom', json_decode(stripslashes($options['oyiso_theme_colors_custom'])));
          if (get_option('oyiso_theme_color') == 'custom') {
            update_option('oyiso_theme_colors', get_option('oyiso_theme_colors_custom'));
          }
        }

        // カラーオプションを処理
        if ($name == 'oyiso_theme_color' && $value == 'custom') {
          update_option('oyiso_theme_colors', get_option('oyiso_theme_colors_custom'));
        }
      }
  
      // 成功レスポンスを送信
      wp_send_json_success('success');
    } else {
      wp_send_json_error('failed');
    }
  }
}
add_action('wp_ajax_oyiso_options', 'oyiso_options');


// 管理者アバターajax処理
function update_avatar() {
  // ユーザー権限を確認
  if (!current_user_can('administrator')) {
    wp_send_json_error('failed');

  } else {
    if (isset($_POST['avatar_url'])) {
      $avatar_url = $_POST['avatar_url'];
      $user_id = get_current_user_id();
      update_user_meta($user_id, 'avatar_url', $avatar_url);

      if (!empty($avatar_url)) {
        echo 'success';
      } else {
        echo get_avatar_url($user_id);
      }
    } else {
      echo 'failed';
    }
  }
  wp_die();
}
add_action('wp_ajax_update_avatar', 'update_avatar');


// テーマを更新
function oyiso_update() {
  if (!current_user_can('administrator')) {
    wp_send_json_error('failed');
  }
  if (isset($_POST['url']) && isset($_POST['version'])) {
    $url = $_POST['url'];
    $version = $_POST['version'];
    if ($version == wp_get_theme()->get('Version')) {
      wp_send_json_success('既に最新バージョンです！');
    }
    $destination = get_theme_root().'/oyiso.zip';
    $res = file_put_contents($destination, fopen($url, 'r'));
    if ($res !== false) {
      $zip = new ZipArchive;
      $res = $zip->open($destination);
      if ($res === TRUE) {
        $zip->extractTo(get_theme_root().'/oyiso');
        $zip->close();
        wp_send_json_success('更新成功！ページまたはキャッシュを更新してください！');
      } else {
        wp_send_json_error('オンライン更新に失敗しました。<a href="'.$url.'">手動ダウンロード</a>してください！');
      }
    } else {
      wp_send_json_error('オンライン更新に失敗しました。<a href="'.$url.'">手動ダウンロード</a>してください！');
    }
  }
}
add_action('wp_ajax_oyiso_update', 'oyiso_update');
