<?php

// 默认分类封面
function category_default_cover() {
  return file_uri().'/assets/images/cover-category.jpg';
}


// 添加一个图片链接字段到分类中
function add_category_image_field() {
  ?>
  <div class="form-field">
    <label for="category_image">图片链接</label>
    <input type="text" name="category_image" id="category_image" />
    <p>只可添加单张图片，不可填写api，需要获取图片颜色。</p>
  </div>
  <?php
}
add_action('category_add_form_fields', 'add_category_image_field', 10, 2);


// 编辑分类的图片链接
function edit_category_image_field($term) {
  $image_url = get_term_meta($term->term_id, 'category_image', true);
  $category_color = get_term_meta($term->term_id, 'category_image_color', true);
  if (isset($category_color['pri'])) {
    $pri = $category_color['pri'];
    $sub = $category_color['sub'];
    $url = $category_color['url'];
  } else {
    $pri = '';
    $sub = '';
    $url = '';
  }
  $url = $url ? $url : ($image_url ? $image_url : category_default_cover());
  ?>
  <tr class="form-field">
    <th scope="row"><label for="category_image">图片链接</label></th>
    <td>
      <input type="text" name="category_image" id="category_image" value="<?php echo esc_attr($url); ?>" />
      <p class="description">只可添加单张图片，不可填写api，需要获取图片颜色。</p>
    </td>
  </tr>
  <tr class="form-field">
      <th scope="row" valign="top">
        <label for="category_image_color">图片主题色</label>
      </th>
      <td>
        <p class="description"><?=$pri ? '<div style="display:inline-block;border-radius:50%;width:15px;height:15px;background-color:'.$pri.';"></div> ' : ''?><?=$pri ? $pri : '暂未获取颜色'?></p>
        <?=$pri ? '<p class="description"><div style="display:inline-block;border-radius:50%;width:15px;height:15px;background-color:'.$sub.';"></div> '.$sub.'</p>' : ''?>
        <p class="description"><?=$url ? '<img src="'.$url.'" style="max-width:100px;max-height:100px;">' : ''?></p>
      </td>
    </tr>
  <?php
}
add_action('category_edit_form_fields', 'edit_category_image_field', 10, 2);




