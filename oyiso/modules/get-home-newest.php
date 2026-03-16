<?php

function get_home_newest($type, $limit=false) {
  if (!$limit) {
    $limit = get_option('posts_per_page');
  }
  if ($type == 'post') { // 置顶文章
    if (get_option('sticky_posts')) {
      $query = new WP_Query(array(
        'post__in' => get_option('sticky_posts'),
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'ignore_sticky_posts' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
      ));
      return echo_query_posts($query);
    } else {
      return notice('warning', '', "There are <b>no more posts</b> here.");
    }
  } else if ($type == 'moment' || $type == 'gallery') { // 片刻、画展
    $query = new WP_Query(array(
      'post_type' => $type,
      'posts_per_page' => $limit,
      'ignore_sticky_posts' => 1,
      'orderby' => 'date',
      'order' => 'DESC',
    ));
    switch($type) {
      case 'moment':
        return echo_query_moments($query);
        break;
      case 'gallery':
        return echo_query_galleries($query);
        break;
    }
  } else if ($type == 'bookmark') { // 友链
    $categories = get_terms(array(
      'taxonomy' => 'link_category',
      'hide_empty' => false,
      'meta_query' => array(
        array(
          'key' => 'link_category_order',
          'value' => 0,
          'compare' => '>',
        )
      ),
    ));
    $bookmarks = [];
    foreach ($categories as $category) {
      $bookmark = get_bookmarks(array(
        'category' => $category->term_id,
      ));
      if (!empty($bookmark)) {
        $bookmarks = array_merge($bookmarks, $bookmark);
      }
    }
    if (!empty($bookmarks)) {
      usort($bookmarks, function($a, $b) {
        return $b->link_id - $a->link_id;
      });
      $bookmarks = array_slice($bookmarks, 0, $limit);
    }
    return echo_query_bookmarks($bookmarks);
  } else if ($type == 'comment') { // 评论
    $comments = get_comments(array(
      'number' => $limit,
      'status' => 'approve',
      'orderby' => 'comment_date',
      'order' => 'DESC',
    ));
    return echo_query_comments($comments);
  } else if ($type == 'bloger') {
    return echo_query_bloger();
  } else if ($type == 'notice') {
    return echo_query_notice();
  }
}

// 文章
function echo_query_posts($query) {
  $current_cover = 1;
  $output = '<ul>';
  if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();
    $categories = get_the_category();
    $pic = the_cover_url('large', $current_cover);
    $theme_color = theme_color_sync(true) ? 'style="color:'.theme_color_sync(true).'"' : '';
    $output .= '<li class="item">';
    $output .= '<a href="'.get_the_permalink().'">';
    $output .= '<div class="cover">';
    $output .= lazy_load_pic($pic); $current_cover++;
    $output .= '</div>';
    $output .= '<div class="info">';
    $output .= '<div class="top">';
    $output .= '<div class="cate" '.$theme_color.'>';
    if ($categories) {
      foreach ($categories as $category) {
        $output .= '<span class="url" data-href="'.esc_url(get_category_link($category->term_id)).'">
        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg> '.esc_html($category->name).'</span>';
      }
    }
    $output .= '</div>';
    $output .= '<div class="title" title="'.get_the_title().'">'.get_the_title().'</div>';
    $output .= '</div>';
    $output .= '<div class="date">'.get_the_date().'</div>';
    $output .= '</div>';
    $output .= '</a>';
    $output .= '</li>';
  endwhile; else :
    $output .= '<style>main .home1-newest .content .post ul {display: block;}</style>';
    $output .= notice('warning', '', "There are <b>no more posts</b> here.");
  endif; wp_reset_postdata();
  $output .= '</ul>';
  return $output;
}

// 片刻
function echo_query_moments($query) {
  $output = '<ul class="group">';
  if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();
    // 定位：moment_123
    $post_id = get_the_ID();
    $page_num = get_moment_page($post_id);
    $output .= '<li class="item">';
    $output .= '<a href="/moments/page/'.$page_num.'#moment_'.$post_id.'">';
    $output .= '<div class="date">发布于：'.get_the_date('Y年m月d日 H:i').'</div>';
    $output .= '<div class="text">'.replaceAToSpan(get_the_content()).'</div>';
    $output .= '</a>';
    $output .= '</li>';
  endwhile; else :
    $output .= '<style>main .home1-newest .content .moment ul {display: block;}</style>';
    $output .= notice('warning', '', "There are <b>no more moments</b> here.");
  endif; wp_reset_postdata();
  $output .= '</ul>';
  return $output;
}

// 画展
function echo_query_galleries($query) {
  $current_cover = 1;
  $output = '<ul>';
  if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();
    $categories = get_the_terms(get_the_ID(), 'gallery_category');
    $pic = the_cover_url('large', $current_cover);
    $theme_color = theme_color_sync(true) ? 'style="color:'.theme_color_sync(true).'"' : '';
    $output .= '<li class="item">';
    $output .= '<a href="'.get_the_permalink().'">';
    $output .= '<div class="cover">';
    $output .= lazy_load_pic($pic); $current_cover++;
    $output .= '</div>';
    $output .= '<div class="info">';
    $output .= '<div class="top">';
    $output .= '<div class="cate" '.$theme_color.'>';
    if ($categories) {
      foreach ($categories as $category) {
        $output .= '<span class="url" data-href="'.esc_url(get_category_link($category->term_id)).'">
        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg> '.esc_html($category->name).'</span>';
      }
    }
    $output .= '</div>';
    $output .= '<div class="title" title="'.get_the_title().'">'.get_the_title().'</div>';
    $output .= '</div>';
    $output .= '<div class="date">'.get_the_date().'</div>';
    $output .= '</div>';
    $output .= '</a>';
    $output .= '</li>';
  endwhile; else :
    $output .= '<style>main .home1-newest .content .gallery ul {display: block;}</style>';
    $output .= notice('warning', '', "There are <b>no more galleries</b> here.");
  endif; wp_reset_postdata();
  $output .= '</ul>';
  return $output;
}

// 友链
function echo_query_bookmarks($bookmarks) {
  if (empty($bookmarks)) {
    $output = '<style>main .home1-newest .content .bookmark ul {display: block;}</style>';
    $output .= notice('warning', '', "There are <b>no more friends</b> here.");
  }
  $output = '<ul>';
  foreach ($bookmarks as $bookmark) {
    $image = $bookmark->link_image ? $bookmark->link_image : file_uri().'/assets/images/iconshort.png';
    $desc = $bookmark->link_description ? $bookmark->link_description : '这家伙很懒，什么都没写';
    $categories = get_terms([
      'taxonomy' => 'link_category',
      'object_ids' => $bookmark->link_id,
    ]);
    $category_name = '';
    foreach ($categories as $category) {
      $category_name = '<p class="from">来自 '.$category->name.'</p>';
    }
    $output .= '<li>';
    $output .= '<a href="'.$bookmark->link_url.'" target="'.$bookmark->link_target.'">';
    $output .= '<div class="icon">';
    $output .= '<img src="'.$image.'" alt="">';
    $output .= '</div>';
    $output .= '<div class="info">';
    $output .= '<h3>'.$bookmark->link_name.'</h3>';
    $output .= '<p title="'.$desc.'">'.$desc.'</p>';
    $output .= '</div>';
    $output .= '<div class="profile">';
    $output .= '<div class="imgbox">';
    $output .= '<img src="'.$image.'" alt="">';
    $output .= '</div>';
    $output .= '</div>';
    $output .= $category_name;
    $output .= '</a>';
    $output .= '</li>';
  }
  $output .= '</ul>';
  return $output;
}

// 评论
function echo_query_comments($comments) {
  $output = '<ul>';
  if (empty($comments)) {
    $output .= '<style>main .home1-newest .content .comment ul {display: block;}</style>';
    $output .= notice('warning', '', "There are <b>no more comments</b> here.");
  }
  foreach ($comments as $comment) {
    if ($comment->user_id > 0) {
      $name = get_userdata($comment->user_id)->display_name;
      $avatar_url = get_avatar_url($comment->user_id);
    } else {
      $name = $comment->comment_author;
      $avatar_url = get_avatar_url($comment->comment_author_email);
    }
    $comment_parent_id = $comment->comment_parent;
    $comment_parent_author = '';
    if ($comment_parent_id) {
      $comment_parent = get_comment($comment_parent_id);
      $comment_parent_user_id = $comment_parent->user_id;
      if ($comment_parent_user_id > 0) {
        $comment_parent_user_info = get_userdata($comment_parent_user_id);
        $comment_parent_author = $comment_parent_user_info -> display_name;
      } else {
        $comment_parent_author = $comment_parent -> comment_author;
      }
    }
    $post_id = $comment->comment_post_ID;
    if (get_post_type($post_id) != 'moment') {
      $post_url = 'href="'.get_the_permalink($comment->comment_post_ID).'#comment_reply='.$comment->comment_ID.'"';
    } else {
      $post_url = '';
    }
    $content = replaceAToSpan($comment->comment_content);
    if ($comment_parent_author) {
      $comment_content = "<div class='text' title='{$comment->comment_content}'><span class='at'>@{$comment_parent_author}：</span><p>{$content}</p></div>";
    } else {
      $comment_content = "<div class='text' title='{$comment->comment_content}'><p>{$content}</p></div>";
    }

    $output .= '<li>';
    $output .= '<a '.$post_url.'>';
    $output .= '<div class="info">';
    $output .= '<div class="avatar">';
    $output .= '<img src="'.$avatar_url.'" alt="">';
    $output .= '</div>';
    $output .= '<div class="msg">';
    $output .= '<div class="name">'.$name.'</div>';
    $output .= '<div class="date">'.get_date_from_gmt($comment->comment_date, 'Y年m月d日 H:i').'</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= $comment_content;
    $output .= '</a>';
    $output .= '</li>';
  }
  $output .= '</ul>';
  return $output;
}

// 博主
function echo_query_bloger() {
  $bloger_name = get_user_meta(1, 'nickname', true);
  $bloger_desc = get_user_meta(1, 'description', true);
  $output = '<div class="warp">';
  $output .= '<div class="info main-reveal">';
  $output .= '<div class="avatar">';
  $output .= '<img src="'.get_avatar_url(1).'" alt="">';
  $output .= '</div>';
  $output .= '<div class="n-d">';
  $output .= '<div class="name">'.$bloger_name.'</div>';
  $output .= $bloger_desc?'<div class="desc">'.$bloger_desc.'</div>':'';
  $output .= '</div>';
  $output .= '</div>';
  $nav_social = wp_nav_menu(array( 
    'theme_location'  => 'social',
    'container_class' => 'socialize main-reveal',
    'fallback_cb'     => 'home1_social_fallback',
    'echo'            => false
  ));
  if (!empty($nav_social)) {
    $output .= $nav_social;
  } else if ($nav_social === false) {
    $output .= home1_social_fallback();
  }
  $output .= '</div>';
  return $output;
}

// 公告
function echo_query_notice() {
  $text = stripslashes(trim(get_option('oyiso_notice_text')));
  function notice_html($text) {
    return '<div class="notify">
      <div class="notify-header">
        <b>
          <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-notice-full"></use>
          </svg> <span>通知</span>
        </b>
      </div>
      <div class="notify-body">
        <p>'.$text.'</p>
      </div>
    </div>';
  }
  return empty($text) ? notice_html('There are <b>no more notices</b> here.') : notice_html($text);
}