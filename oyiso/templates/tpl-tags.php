<?php
/**
 * Template Name: タグ一覧
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="tpl-tag">
  <div class="screen">
    <div class="screen-title main-reveal">Post Tag</div>
    <?php
      $tags = get_tags();
      if($tags) {
        // $svg = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-tag-fill"></use></svg>';
        echo "<ul>";
        // var_dump($tags);
        foreach ($tags as $tag) {
          ?>
            <li class="main-reveal">
              <a href="<?=get_tag_link($tag->term_id)?>">
                <div class="info">
                  <span>Total: <?=$tag->count > 1 ? "$tag->count Posts" : "$tag->count Post"?></span>
                  <h2><?=$tag->name?></h2>
                  <?=$tag->description ? '<p>'.$tag->description.'</p>' : ''?></p>
                </div>
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tag-fill"></use></svg>
              </a>
            </li>
          <?php
        }
        echo "</ul>";
      } else {
        echo notice('warning','', "There are <b>no more tags</b> left.");
      }
    ?>
  </div>
</section>

<?=get_footer()?>