<?php
/**
 * Template Name: 画展模板
 */
?>

<?php get_header(); ?>

<section class="lastest gallery">
  <div class="screen">
    <div class="screen-title main-reveal">Galleries</div>
    <ul>
      <?php
        // 创建 gallery 实例
        $gallery = new WP_Query(
          array(
            'post_type' => 'gallery',  // 文章类型
            // 'posts_per_page' => 9, // 每页显示的文章数量
            'paged' => get_query_var('paged', 1), // 当前页数
            'orderby' => 'date', // 日期类型的排序
            'order' => 'DESC' // 降序排列
          )
        );

        // 开始循环
        $currentCover = 1;
        if ($gallery->have_posts()) :
        while ($gallery->have_posts()) : $gallery->the_post();

        // 分类
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