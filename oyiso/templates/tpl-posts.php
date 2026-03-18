<?php
/**
 * Template Name: 投稿一覧
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="popular">
  <div class="screen">
    <div class="screen-title main-reveal">Posts</div>
    <ul>
      <?php
        // allposts インスタンスを作成
        $allposts = new WP_Query(
          array(
            'post_type' => 'post',  // 投稿タイプ
            // 'posts_per_page' => 4, // 1ページあたりの表示数
            'paged' => get_query_var('paged', 1), // 現在のページ番号
            'ignore_sticky_posts' => 1, // 固定投稿を除外
            'orderby' => 'date', // 日付順
            'order' => 'DESC' // 降順
          )
        );
        
        // ループ
        $currentCover = 1;
        if ($allposts->have_posts()) :
        while ($allposts->have_posts()) : $allposts->the_post();

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
        <?=notice('warning', '', "There are <b>no more posts</b> here.")?>
      <?php endif; wp_reset_postdata();?>
    </ul>
    <?=the_pagination($allposts)?>
  </div>
</section>

<?=get_footer()?>