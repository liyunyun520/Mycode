<?=get_header()?>

<!-- <section class="post-category">
  <div class="screen">
  <div class="screen-title main-reveal">Post Tag</div>
    <div class="cate main-reveal">
      <?php
        $current_tag = get_queried_object();
        $tag_id = $current_tag->term_id;
        $tags = get_tags();
        if($tags) {
          $svg = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-tag-fill"></use></svg>';
          // var_dump($tags);
          foreach ($tags as $tag) {
            if($tag->term_id == $tag_id) {
              $active = 'active';
            } else {
              $active = '';
            }
            ?>
              <a class="<?=$active?>" href="<?=get_tag_link($tag->term_id)?>"><?=$svg?><?=$tag->name?><span><?=$tag->count?></span></a>
            <?php
          }
        } else {
          echo notice('warning','', "There are <b>no more tags</b> left.");
        }
        
      ?>
    </div>
  </div>
</section> -->

<section class="popular current-tag">
  <div class="screen">
  <div class="screen-title main-reveal">Post Tag - <?=get_queried_object()->name?></div>
    <ul>
      <?php
        // current_tag_post インスタンスを作成
        $current_tag = get_queried_object();
        $tag_id = $current_tag->term_id;

        $current_tag_post = new WP_Query(
          array(
            'post_type' => 'post', // 投稿タイプ
            // 'posts_per_page' => 6, // 1ページあたりの表示数
            'paged' => get_query_var('paged', 1), // 現在のページ番号
            'ignore_sticky_posts' => 1, // 固定投稿を除外
            'tax_query' => array(
              array(
                'taxonomy' => 'post_tag', // タグのタクソノミー
                'field' => 'id',
                'terms' => $tag_id // 現在のタグID
                )
              ),
            'order' => 'DESC' // 降順
          )
        );
        
        // ループ
        $currentCover = 1;
        if ($current_tag_post->have_posts()) :
        while ($current_tag_post->have_posts()) : $current_tag_post->the_post();
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
                    $tags = get_the_tags();
                    if ($tags) {
                      foreach ($tags as $tag) {
                        // echo '<span class="url" data-href="'.esc_url(get_tag_link($tag->term_id)).'">'.esc_html($tag->name).'</span><span class="separator">/</span>';
                        echo '<span class="url" data-href="'.esc_url(get_tag_link($tag->term_id)).'">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tag-fill"></use></svg>'.esc_html($tag->name).'</span>';
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
        <?=notice('warning', '', "There are <b>no articles</b> under this tag.")?>
      <?php endif; wp_reset_postdata();?>
    </ul>
    <?=the_pagination($current_tag_post)?>
  </div>
</section>

<?=get_footer()?>