<?php

add_action('wp_ajax_get_theme_info', 'get_theme_info');
add_action('wp_ajax_nopriv_get_theme_info', 'get_theme_info');
function get_theme_info() {
  // 获取当前主题版本
  $theme = wp_get_theme();

  // 返回版本信息
  wp_send_json_success(array(
    'name' => $theme->get('Name'),
    'version' => $theme->get('Version'),
  ));
}