<?php 

// 新建分类或编辑分类时获取图片url和主题色
add_action('created_category', 'add_category_image', 10, 2);
add_action('edited_category', 'update_category_image', 10, 2);

function add_category_image($term_id) {
  if (isset($_POST['category_image'])) {
    $category_image = $_POST['category_image'];
    //如果关闭则不执行
    if (get_option('oyiso_cover_color')) {
      $image_theme_color = get_image_theme_color($category_image);
    } else {
      $image_theme_color = '';
    }
    

    if (!empty($category_image)) {
      add_term_meta($term_id, 'category_image', $category_image);
    }

    if (!empty($image_theme_color) && isset($image_theme_color['pri'])) {
      add_term_meta($term_id, 'category_image_color', $image_theme_color);
    }
  }
}

function update_category_image($term_id) {
  if (isset($_POST['category_image'])) {
    $category_image = $_POST['category_image'];
    $category_image_old = get_term_meta($term_id, 'category_image', true);
    $category_image_color = get_term_meta($term_id, 'category_image_color', true);
    
    if (!empty($category_image)) {
      update_term_meta($term_id, 'category_image', $category_image);
    }

    if (($category_image != $category_image_old) || empty($category_image_color)) {
      if (!empty($category_image)) {
        //如果关闭则不执行
        if (get_option('oyiso_cover_color')) {
          $image_theme_color = get_image_theme_color($category_image);
        } else {
          $image_theme_color = '';
        }
        if (!empty($image_theme_color) && isset($image_theme_color['pri'])) {
          update_term_meta($term_id, 'category_image_color', $image_theme_color);
        } else {
          update_term_meta($term_id, 'category_image_color', '');
        }
      } else {
        update_term_meta($term_id, 'category_image', '');
        update_term_meta($term_id, 'category_image_color', '');
      }
    }
  }
}


// 获取主色调和副色调
function category_theme_color() {
  $image_theme_color = get_term_meta(get_queried_object_id(), 'category_image_color', true);
  $object = new stdClass();
  if(!empty($image_theme_color) && get_option('oyiso_cover_color')) {
    if (isset($image_theme_color['pri']) && isset($image_theme_color['sub'])) {
      $object->pri = $image_theme_color['pri'];
      $object->sub = $image_theme_color['sub'];
      return $object;
    } else {
      $object->pri = '';
      $object->sub = '';
      return $object;
    }
  } else {
    $object->pri = '';
    $object->sub = '';
    return $object;
  }
}

// 色彩同步
function category_theme_color_sync() {
  if (get_option('oyiso_cover_color') && !empty(category_theme_color()->pri)) {
    $category_theme_color = category_theme_color();
    root_color($category_theme_color->pri, $category_theme_color->sub);
  } else {
    $category_theme_color = get_option('oyiso_theme_colors');
    if (isset($category_theme_color->pri) && !empty($category_theme_color->pri)) {
      root_color($category_theme_color->pri, $category_theme_color->sub);
    }
  }
}