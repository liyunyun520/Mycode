<?php

// get_avatar
add_filter('get_avatar', 'oyiso_avatar', 999, 5);
function oyiso_avatar($avatar, $id_or_email, $size, $default, $alt) {
  $user = false;
  $email = '';
  $user_avatar = '';

  if (is_numeric($id_or_email)) {
    $id = (int) $id_or_email;
    $user = get_user_by('id' , $id);
    if (is_object($user)) {
      $email = $user->data->user_email ? $user->data->user_email : '';
    }
  } elseif (is_object($id_or_email)) {
    if (!empty($id_or_email->user_id)) {
      $id = (int) $id_or_email->user_id;
      $user = get_user_by('id' , $id);
      $email = $user->data->user_email ? $user->data->user_email : '';
    } else if (!empty($id_or_email->comment_author_email)) {
      $email = $id_or_email->comment_author_email;
    }
  } else {
    $email = $id_or_email;
  }

  if ($user && is_object($user)) {
    $user_avatar = get_user_meta($user->data->ID, 'avatar_url', true);
  }

  if (!empty($user_avatar)) {
    $avatar = "<img alt='{$alt}' src='{$user_avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
  } else {
    // 如果没有设置自定义头像，显示cravatar的头像
    if ($email && preg_match('/^\d+@qq\.com$/', $email)) {
      $avatar_url = 'https://q1.qlogo.cn/g?b=qq&nk='.explode('@', $email)[0].'&s=640';
      $avatar = "<img src='$avatar_url' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    } else {
      $address = strtolower(trim($email));
      $hash    = md5($address);
      $avatar = "<img alt='{$alt}' src='https://cravatar.cn/avatar/{$hash}?s=400&d=mp' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    }
  }

  return $avatar;
}


// get_avatar_url
add_filter('get_avatar_url', 'oyiso_avatar_url', 999, 3);
function oyiso_avatar_url($url, $id_or_email, $args) {
  $user = false;
  $email = '';
  $user_avatar = '';

  if (is_numeric($id_or_email)) {
    $id = (int) $id_or_email;
    $user = get_user_by('id' , $id);
    if (is_object($user)) {
      $email = $user->data->user_email ? $user->data->user_email : '';
    }
  } elseif (is_object($id_or_email)) {
    if (!empty($id_or_email->user_id)) {
      $id = (int) $id_or_email->user_id;
      $user = get_user_by('id' , $id);
      $email = $user->data->user_email ? $user->data->user_email : '';
    } else if (!empty($id_or_email->comment_author_email)) {
      $email = $id_or_email->comment_author_email;
    }
  } else {
    $email = $id_or_email;
  }

  if ($user && is_object($user)) {
    $user_avatar = get_user_meta($user->data->ID, 'avatar_url', true);
  }

  if (!empty($user_avatar)) {
    $avatar_url = $user_avatar;
  } else {
    // 如果没有设置自定义头像，返回cravatar的头像URL
    if ($email && preg_match('/^\d+@qq\.com$/', $email)) {
      $avatar_url = 'https://q1.qlogo.cn/g?b=qq&nk='.explode('@', $email)[0].'&s=640';
    } else {
      $address = strtolower(trim($email));
      $hash = md5($address);
      $avatar_url = "https://cravatar.cn/avatar/{$hash}?s=400&d=mp";
    }
  }
  
  return $avatar_url;
}