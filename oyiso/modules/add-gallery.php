<?php

function oyiso_gallery() {
  $labels = array(
    'name' => '画展',
    'singular_name' => '画展',
    'all_items' => '所有画展',
    'add_new' => '新增画展',
    'add_new_item' => '新增画展',
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
    'has_archive' => true,
    'show_in_rest' => false,
    'menu_position' => 7,
    'menu_icon' => 'dashicons-images-alt',
    'supports' => $supports,
    'rewrite' => array(
      'with_front' => false,
    ),
    'query_var' => true,
    'taxonomies' => array('gallery_category'),
  );
  $category_labels = array(
    'name' => '画展分类',
    'singular_name' => '画展分类',
  );
  $category_args = array(
    'labels' => $category_labels,
    'show_ui' => true,
    'show_in_rest' => false,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'gallery_category'),
    'hierarchical' => true,
  );
  register_post_type('gallery', $args);
  register_taxonomy('gallery_category', 'gallery', $category_args);
}
add_action('init', 'oyiso_gallery');