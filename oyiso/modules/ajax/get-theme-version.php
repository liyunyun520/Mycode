<?php

add_action('wp_ajax_get_theme_info', 'get_theme_info');
add_action('wp_ajax_nopriv_get_theme_info', 'get_theme_info');
function get_theme_info() {
  // 現在のテーマバージョンを取得
  $theme = wp_get_theme();

  // バージョン情報を返す
  wp_send_json_success(array(
    'name' => $theme->get('Name'),
    'version' => $theme->get('Version'),
  ));
}