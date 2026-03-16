<?php

function oyiso_moment() {
  $labels = array(
    'name' => '片刻',
    'singular_name' => '片刻',
    'all_items' => '所有片刻',
    'add_new' => '写片刻',
    'add_new_item' => '写片刻',
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
    'publicly_queryable' => false, // 禁止单独页面查看
    'show_in_nav_menus' => false, // 禁止在导航菜单中显示
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


// 添加位置复选框
function oyiso_add_location() {
  add_meta_box(
    'oyiso_location',
    '位置信息',
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
      <span>分享位置</span>
    </label>
  </div>';
}

// 保存位置信息
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