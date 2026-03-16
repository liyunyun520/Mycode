<?php
/**
 * Template Name: 文章列表
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="popular">
  <div class="screen">
    <div class="screen-title main-reveal">Posts</div>
    <ul>
      <?php
        // 创建 allposts 实例
        $allposts = new WP_Query(
          array(
            'post_type' => 'post',  // 文章类型
            // 'posts_per_page' => 4, // 每页显示的文章数量
            'paged' => get_query_var('paged', 1), // 当前页数
            'ignore_sticky_posts' => 1, // 排除置顶文章
            'orderby' => 'date', // 日期类型的排序
            'order' => 'DESC' // 降序排列
          )
        );
        
        // 循环
        $currentCover = 1;
        if ($allposts->have_posts()) :
        while ($allposts->have_posts()) : $allposts->the_post();

        // 获得分类
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
                <div class="title"><?=get_the_title() ? the_title() : '无标题'?></div>
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