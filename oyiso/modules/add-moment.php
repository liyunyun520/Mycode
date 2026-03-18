<?php

function oyiso_moment() {
  $labels = array(
    'name' => '一瞬',
    'singular_name' => '一瞬',
    'all_items' => 'すべての一瞬',
    'add_new' => '一瞬を書く',
    'add_new_item' => '一瞬を書く',
  );
  $supports = array(
    'title', 
    'editor', 
    'comments',
    'author',
    'revisions',
    'thumbnail', 
    'excerpt', 
    'post-formats',
    // 'custom-fields', 
  );
  $args = array (
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => false, // 単独ページでの閲覧を禁止
    'show_in_nav_menus' => false, // ナビゲーションメニューへの表示を禁止
    'has_archive' => true,
    'show_in_rest' => true,
    'menu_position' => 6,
    'menu_icon' => 'dashicons-format-quote',
    'supports' => $supports,
    'rewrite' => array(
      'with_front' => false,
    ),
    'query_var' => true,
    'taxonomies' => array('moment_category'),
  );
  register_post_type('moment', $args);
}
add_action('init', 'oyiso_moment');


// 位置チェックボックスを追加
function oyiso_add_location() {
  add_meta_box(
    'oyiso_location',
    '位置情報',
    'oyiso_location_callback',
    'moment',
    'advanced',
    'high'
  );
}
add_action('add_meta_boxes', 'oyiso_add_location');
function oyiso_location_callback($post) {
  $location_enabled = get_post_meta($post->ID, 'oyiso_location_enabled', true);

  echo '
  <div class="components-panel__row">
    <label class="components-checkbox-control__label" for="oyiso_location_enabled">
      <input id="oyiso_location_enabled" name="oyiso_location_enabled" type="checkbox" value="1" '.checked(1, $location_enabled, false).'>
      <span>位置をシェア</span>
    </label>
  </div>';
}

// 位置情報を保存
function oyiso_save_post($post_id, $post, $update) {
  $location_enabled = isset($_POST['oyiso_location_enabled']) && $_POST['oyiso_location_enabled'] == '1';
  update_post_meta($post_id, 'oyiso_location_enabled', $location_enabled);

  if ($location_enabled) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $location = get_user_location($ip, ' · ');
    if (!empty($location)) {
      update_post_meta($post_id, 'oyiso_location', $location);
    }
  }
}
add_action('save_post', 'oyiso_save_post', 10, 3);