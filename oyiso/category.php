<?=get_header()?>

<?=category_theme_color_sync()?>

<section class="archive-category post-category-cover">
  <div class="screen banner-reveal">
    <a class="info">
      <?php 
        $current_category = get_queried_object();
        $image_url = get_term_meta($cat, 'category_image', true); 
        $image_color = get_term_meta($cat, 'category_image_color', true);
        if ($image_color && isset($image_color['url'])) {
          $url = $image_color['url'];
        } else {
          $url = '';
        }
        $url = $url ? $url : ($image_url ? $image_url : category_default_cover());
      ?>
      <?php
        $pic = $url;
        echo lazy_load_pic($pic);
      ?>
      <div class="cate_info">
        <h2><?=$current_category->name?></h2>
        <p><?=$current_category->description ? $current_category->description : 'このカテゴリーには説明がありません'?></p>
      </div>
      <span>全<?=get_post_count($cat, 'category')?>件</span>
    </a>
  </div>
</section>

<section class="post-category">
  <div class="screen">
  <div class="screen-title main-reveal">Post Category</div>
    <div class="cate main-reveal">
      <?php
        $categories = get_categories();
        if($categories) {
          $svg = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg>';
          // var_dump($cat);
          foreach ($categories as $category) {
            if($category->term_id == $cat) {
              $active = 'active';
            } else {
              $active = '';
            }
            ?>
              <a class="<?=$active?>" href="<?=get_category_link($category->term_id)?>"><?=$svg?><?=$category->name?><span><?=get_post_count($category->term_id, 'category')?></span></a>
            <?php
          }
        } else {
          echo notice('warning', '', "There are <b>no more categories</b> left.");
        }
        
      ?>
    </div>
  </div>
</section>

<section class="popular current-category">
  <div class="screen">
    <ul>
      <?php
        // current_category_post インスタンスを作成
        $current_category_post = new WP_Query(
          array(
            'post_type' => 'post', // 投稿タイプ
            // 'posts_per_page' => 6, // 1ページあたりの表示数
            'paged' => get_query_var('paged', 1), // 現在のページ番号
            'ignore_sticky_posts' => 1, // 固定投稿を除外
            'cat' => $cat, // 現在のカテゴリーID
            'order' => 'DESC' // 降順
          )
        );
        
        // ループ
        $currentCover = 1;
        if ($current_category_post->have_posts()) :
        while ($current_category_post->have_posts()) : $current_category_post->the_post();

        // カテゴリーを取得
        $categories = get_the_category();
      ?>
        <li class="main-reveal">
          <a href="<?=the_permalink()?>">
            <div class="cover">
              <div class="box">
                <?php
                  $pic = the_cover_url('medium', $currentCover);
                  echo lazy_load_pic($pic); $currentCover++;
                ?>
              </div>
            </div>
            <div class="info">
              <div class="top">
                <div class="cate" <?=theme_color_sync(true) ? 'style="color:'.theme_color_sync(true).'"' : ''?>>
                  <?php
                    if ($categories) {
                      foreach ($categories as $category) {
                        echo '<span class="url" data-href="'.esc_url(get_category_link($category->term_id)).'">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg> '.esc_html($category->name).'</span>';
                      }
                    }
                  ?>
                </div>
                <div class="title"><?=get_the_title() ? the_title() : 'タイトルなし'?></div>
              </div>
              <div class="date"><?=get_the_date()?><span class="drop">·</span><?=get_post_views()?> Views</div>
            </div>
          </a>
        </li>
      <?php endwhile; else : ?>
        <style>
          main .popular ul {
            display: block;
          }
        </style>
        <?=notice('warning', '', "There are <b>no articles</b> under this category.")?>
      <?php endif; wp_reset_postdata();?>
    </ul>
    <?=the_pagination($current_category_post)?>
  </div>
</section>

<?=get_footer()?>