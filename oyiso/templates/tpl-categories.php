<?php
/**
 * Template Name: 分类模板
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="tpl-category">
  <div class="screen">
    <div class="screen-title main-reveal">Post Category</div>
    <!-- <style>
      main .tpl-category ul li:nth-child(1):hover a {
        border: 2px solid red;
        background-color: red;
      }
    </style> -->
    <?php
      $categories = get_categories();
      if($categories) {
        $category_color = array();
        echo "<ul>";
        foreach ($categories as $category) {
          if (isset(get_term_meta($category->term_id, 'category_image_color', true)['pri']) && get_option('oyiso_cover_color')) {
            $category_color[] = get_term_meta($category->term_id, 'category_image_color', true)['pri'];
          } else {
            $category_color[] = '';
          }
          ?>
            <li class="main-reveal">
              <a href="<?=get_category_link($category->term_id)?>" data-color="">
                <div class="info">
                  <span>Total: <?=get_post_count($category->term_id, 'category') > 1 ? get_post_count($category->term_id, 'category')." Posts" : get_post_count($category->term_id, 'category')." Post"?></span>
                  <h2><?=$category->name?></h2>
                  <p><?=$category->description ? $category->description : '此分类暂时没有描述'?></p>
                </div>
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg>
              </a>
            </li>
          <?php
        }
        echo "</ul>";
        echo '<style>';
        foreach ($category_color as $i => $color) {
          if (empty($color)) {
            continue;
          }
          echo 
          'main .tpl-category ul li:nth-child('.($i+1).'):hover a {
            border: 2px solid '.$color.';
            background-color: '.$color.'1a;
          }
          main .tpl-category ul li:nth-child('.($i+1).') a svg {
            color: '.$color.'1a;
          }';
        }
        echo '</style>';
      } else {
        echo notice('warning', '', "There are <b>no more categories</b> left.");
      }
      
    ?>
  </div>
</section>

<?=get_footer()?>