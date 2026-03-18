<?php

function oyiso_gallery() {
  $labels = array(
    'name' => 'ギャラリー',
    'singular_name' => 'ギャラリー',
    'all_items' => 'すべてのギャラリー',
    'add_new' => 'ギャラリーを追加',
    'add_new_item' => 'ギャラリーを追加',
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
    'name' => 'ギャラリーカテゴリー',
    'singular_name' => 'ギャラリーカテゴリー',
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