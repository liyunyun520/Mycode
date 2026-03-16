<?=get_header()?>

<section class="post-category">
  <div class="screen">
    <div class="screen-title main-reveal">Gallery Category</div>
    <div class="cate main-reveal">
      <?php
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
          $category_id = $term->term_id;
        }
        $categories = get_terms(array(
          'taxonomy' => 'gallery_category'
        ));
        if($categories) {
          $svg = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg>';
          foreach ($categories as $category) {
            if($category->term_id == $category_id) {
              $active = 'active';
            } else {
              $active = '';
            }
            ?>
              <a class="<?=$active?>" href="<?=get_category_link($category->term_id)?>"><?=$svg?><?=$category->name?><span><?=get_post_count($category->term_id, 'gallery_category')?></span></a>
            <?php
          }
        } else {
          echo notice('warning', '', "There are <b>no more categories</b> left.");
        }
      ?>
    </div>
  </div>
</section>

<section class="lastest gallery-category">
  <div class="screen">
    <ul>
      <?php
        // gallery インスタンスを作成
        $gallery = new WP_Query(
          array(
            'post_type' => 'gallery',  // 投稿タイプ
            // 'posts_per_page' => 9, // 1ページあたりの表示数
            'paged' => get_query_var('paged', 1), // 現在のページ番号
            'tax_query' => array(
              array(
                'taxonomy' => 'gallery_category',
                'terms' => $category_id,
              ),
            ),
            'orderby' => 'date', // 日付順
            'order' => 'DESC' // 降順
          )
        );

        // ループ開始
        $currentCover = 1;
        if ($gallery->have_posts()) :
        while ($gallery->have_posts()) : $gallery->the_post();

        // カテゴリー
        $categories = get_the_terms(get_the_ID(), 'gallery_category');
      ?>
        <li class="quarter-width main-reveal">
        <a href="<?=the_permalink()?>">
          <div class="cover">
            <div class="pic-box">
              <?php
                  $pic = the_cover_url('large', $currentCover);
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
            <div class="datetime"><?=get_the_date()?></div>
          </div>
        </a>
      </li>
      <?php endwhile; else : ?>
        <style>
          main .lastest ul {
            display: block;
          }
        </style>
        <?=notice('warning', '', "There are currently <b>no gallery</b> available.")?>
      <?php endif; wp_reset_postdata();?>
    </ul>
    <?=the_pagination($gallery)?>
  </div>
</section>

<?=get_footer()?>