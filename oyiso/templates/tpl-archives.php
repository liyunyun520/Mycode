<?php
/**
 * Template Name: アーカイブ
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="archive-tag">
  <div class="screen">
  <?php
    $tags = get_terms(array(
      'taxonomy' => 'post_tag', // タグのタクソノミー名
      'hide_empty' => true // 関連記事があるタグのみ表示
    ));
    $tags_with_posts_count = 0;
    foreach ($tags as $tag) {
      // タグに関連記事があるかチェック
      $posts_count = $tag->count;
      if ($posts_count > 0) {
          $tags_with_posts_count++;
      }
    }
  ?>
  <div class="screen-title main-reveal">All Tags (<?=$tags_with_posts_count?>)</div>
  <?php
    $tags = get_tags();
    $svg = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-tag-fill"></use></svg>';
    if ($tags) {
      echo '<div class="tag main-reveal">';
      foreach($tags as $tag) {
        ?>
          <a href="<?=get_tag_link($tag->term_id)?>"><?=$svg?><?=$tag->name?><span><?=$tag->count?></span></a>
        <?php
      }
      echo '</div>';
    } else {
      echo notice('warning', '', "There are currently <b>no tags</b> available.");
    }
    
  ?>
</section>

<section class="archive-category">
  <div class="screen">
    <?php
      $categories = get_categories();
      $category_count = 0;
      foreach ($categories as $category) {
        // 現在のカテゴリーに記事があるかチェック
        $category_posts = get_posts(array(
          'category' => $category->term_id,
          'numberposts' => 1, // 1記事取得すれば十分
        ));
    
        // 現在のカテゴリーに記事がある場合、カテゴリーカウンターを増加
        if ($category_posts) {
          $category_count++;
        }
      }
    ?>
    <div class="screen-title main-reveal">All Categories (<?=$category_count?>)</div>
    <?php
      $categories = get_categories();
      $parent_categories = get_categories('parent=0');
      if ($categories) {
        foreach($parent_categories as $parent_category) {
          $image_url = get_term_meta($parent_category->term_id, 'category_image', true);
          $image_color = get_term_meta($parent_category->term_id, 'category_image_color', true);
          if ($image_color && isset($image_color['url'])) {
            $url = $image_color['url'];
            $color = isset($image_color['pri']) ? $image_color['pri'] : '';
          } else {
            $color = '';
            $url = '';
          }
          $url = $url ? $url : ($image_url ? $image_url : category_default_cover());
          $color = $color ? $color : '';
          ?>
            <div class="parent_category main-reveal">
              <a href="<?=get_category_link($parent_category->term_id)?>">
                <?php
                  $pic = $url;
                  echo lazy_load_pic($pic);
                ?>
                <div class="cate_info">
                  <h2><?=$parent_category->name?></h2>
                  <p><?=$parent_category->description ? $parent_category->description : 'このカテゴリーには説明がありません'?></p>
                </div>
                <span <?=$color ? 'style="background-color:'.$color.';"': ''?>>全<?=get_post_count($parent_category->term_id, 'category')?>件</span>
              </a>
            </div>
            <ul>
              <?php
                foreach($categories as $categorie) {
                  $image_url = get_term_meta($categorie->term_id, 'category_image', true);
                  $image_color = get_term_meta($categorie->term_id, 'category_image_color', true);
                  if ($image_color && isset($image_color['url'])) {
                    $url = $image_color['url'];
                    $color = isset($image_color['pri']) ? $image_color['pri'] : '';
                  } else {
                    $color = '';
                    $url = '';
                  }
                  $url = $url ? $url : ($image_url ? $image_url : category_default_cover());
                  $color = $color ? $color : '';
                  if($parent_category->term_id == $categorie->parent) {
                  ?>
                    <li class="main-reveal">
                      <a href="<?=get_category_link($categorie->term_id)?>">
                        <?php
                          $pic = $url;
                          echo lazy_load_pic($pic);
                        ?>
                        <div class="cate_info">
                          <h2><?=$categorie->name?></h2>
                          <p><?=$categorie->description ? $categorie->description : 'このカテゴリーには説明がありません'?></p>
                        </div>
                        <span <?=$color ? 'style="background-color:'.$color.';"': ''?>>全<?=$categorie->count?>件</span>
                      </a>
                    </li>
                  <?php
                  }
                }
              ?>
            </ul>
          <?php
        }
      } else {
        echo notice('warning', '', "There are currently <b>no categories</b> available.");
      }
      
    ?>

  </div>
</section>

<section class="post-all">
  <div class="screen">
  <div class="screen-title main-reveal">All Posts (<?=wp_count_posts()->publish?>)</div>
  
  <?php
    // 全記事を取得
    $args = array(
      'post_type' => 'post', // 投稿タイプ post
      'posts_per_page' => -1 // 全記事を取得
    );
    $posts = get_posts($args);
    if ($posts) {
      // 年月でグループ化した記事を格納する配列を作成
      $grouped_posts = array();
      // 記事を年月でグループ化
      foreach ($posts as $post) {
        $year = get_the_date('Y', $post->ID);
        $month = get_the_date('m', $post->ID);
        $grouped_posts[$year][$month][] = $post;
      }
      $item = 1;
      // 年月でグループ化した記事をループ出力
      foreach ($grouped_posts as $year => $months) {
        if ($item == 1) {
          $class_main = 'main-reveal';
          $class_img = 'img-reveal';
          $class_text = 'text-reveal';
        } else {
          $class_main = '';
          $class_img = '';
          $class_text = '';
        }
        echo '<div class="post-year '.$class_img.'"><h2 class="'.$class_text.'">'.$year.'年</h2>';
        foreach ($months as $month => $posts) {
          echo '<div class="post-month"><h3 class="'.$class_main.'">'.date('n月', mktime(0, 0, 0, $month, 1)).'</h3>';
          echo '<ul>';
          foreach ($posts as $post) {
            if ($post->post_title) {
              $post_title = $post->post_title;
            } else {
              $post_title = 'タイトルなし';
            }
            echo '<li class="'.$class_main.'"><span>'.get_the_date('j日').'<i class="rod"> -</i></span><p><a href="'.get_permalink($post->ID).'">'.$post_title.'</a></p></li>';
          }
          echo '</ul></div>';
        }
        echo "</div>";
        $item ++;
      }
    } else {
      echo notice('warning', '', "There are currently <b>no posts</b> available.");
    }
   
  ?>
</section>


<?=get_footer()?>