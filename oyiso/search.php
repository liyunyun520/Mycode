<?php

// 検索クエリを取得
$search_keyword = get_search_query();

// search_query インスタンスを作成
$search_query = new WP_Query(
  array(
    's' => $search_keyword, // 検索クエリ
    'post_type' => 'post',  // 投稿タイプ
    'paged' => get_query_var('paged', 1), // 現在のページ番号
    'ignore_sticky_posts' => 1, // 固定投稿を除外
    'orderby' => 'date', // 日付順
    'order' => 'DESC' // 降順
  )
);
// var_dump($search_query);

if (!$search_query->have_posts()) {
  status_header(404);
}

?>

<?=get_header()?>

<section class="popular">
  <div class="screen">
  <div class="screen-title main-reveal">Search - <?=$search_keyword?></div>
    <ul>
      <?php
        // ループ
        $currentCover = 1;
        if ($search_query->have_posts()) :
        while ($search_query->have_posts()) : $search_query->the_post();

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
        <?=notice('warning', '', "<b>No relevant content</b> found in the search.")?>
      <?php endif; wp_reset_postdata(); ?>
    </ul>
    <?=the_pagination($search_query)?>
  </div>
</section>

<?=get_footer()?>