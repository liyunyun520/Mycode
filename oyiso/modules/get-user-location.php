<?php
if (!defined('ABSPATH')) {
  exit;
}

// ユーザー位置情報を取得
function get_user_location($ip, $showcity=false) {
  // if (get_option('oyiso_comments_ip')) {
    if (!$ip) {
      return '不明';
    }

    $api = get_option('oyiso_comments_ip_api');
    $key = get_option('oyiso_comments_ip_api_key');

    if ($api == 'tencent' && $key) {
      $location = get_location_by_tencent($ip, $key, $showcity);

    } else if ($api == 'baidu' && $key) {
      $location = get_location_by_baidu($ip, $key, $showcity);

    } else if ($api == 'ipinfo' && $key) {
      $location = get_location_by_ipinfo($ip, $key, $showcity);
      
    } else if ($api == 'free') {
      $location = get_location_by_free($ip, $showcity);
    } else {
      $location = '不明';
    }
    if (is_string($showcity)) {
      return is_array($location) ? implode($showcity, $location) : $location;
    } else {
      return is_array($location) ? implode('<span class="dot">·</span>', $location) : $location;
    }
    
  // }
}


// Tencent位置サービス（国内）
function get_location_by_tencent($ip, $key, $showcity) {
  $url = "https://apis.map.qq.com/ws/location/v1/ip?key=$key&ip=$ip";
  $response = wp_remote_get($url, array('timeout' => 5));
  if (is_wp_error($response)) {
    return '不明';
  }
  $data = json_decode(wp_remote_retrieve_body($response), true);
  if (!is_array($data)) {
    return '不明';
  }
  
  $status = $data['status'] ?? -1;
  
  if ($status === 0) { // 成功
    $province = $data['result']['ad_info']['province'] ?? ''; // 県
    $city = $data['result']['ad_info']['city'] ?? ''; // 都市
    $nation = $data['result']['ad_info']['nation'] ?? ''; // 国
    if ($showcity) {
      if ($province == $city) {
        return $province ?: ($nation ?: '中国');
      } else {
        $location = array_filter([$province, $city]);
        return $location ? $location : ($nation ?: '中国');
      }
      
    } else {
      return $province ?: ($city ?: ($nation ?: '中国'));
    }

  } else if ($status === 375) { // ローカルネットワーク
    return 'ローカルネットワーク';

  } else if ($status === 382) { // IPを特定できません
    return get_location_by_free($ip, $showcity);

  } else {
    return '不明';
  }
}


// Baiduスマート位置情報（国内）
function get_location_by_baidu($ip, $ak, $showcity) {
  $url = "https://api.map.baidu.com/location/ip?ak=$ak&ip=$ip";
  $response = wp_remote_get($url, array('timeout' => 5));
  if (is_wp_error($response)) {
    return '不明';
  }
  $data = json_decode(wp_remote_retrieve_body($response), true);
  if (!is_array($data)) {
    return '不明';
  }
  
  $status = $data['status'] ?? -1;
  
  if ($status === 0) { // 成功
    $province = $data['content']['address_detail']['province'] ?? '';
    $city = $data['content']['address_detail']['city'] ?? '';
    if ($showcity) {
      $location = array_filter([$province, $city]);
      return $location ? $location : '中国';
    } else {
      return $province ?: ($city ?: '中国');
    }

  } else if ($status === 1) { // IPを特定できません
    return get_location_by_free($ip, $showcity);

  } else {
    return '不明';
  }
}


// ipinfo.io（海外）
function get_location_by_ipinfo($ip, $token, $showcity) {
  $url = "https://ipinfo.io/$ip?token=$token";
  $response = wp_remote_get($url, array('timeout' => 5));
  if (is_wp_error($response)) {
    return '不明';
  }
  $data = json_decode(wp_remote_retrieve_body($response), true);
  if (!is_array($data)) {
    return '不明';
  }
  $province = isset($data['region']) ? $data['region'] : '';
  $city = isset($data['city']) ? $data['city'] : '';
  $country = isset($data['country']) ? $data['country'] : '';
  $status = isset($data['status']) ? $data['status'] : '';

  if ($status != '404') { // 成功
    if ($showcity) {
      if ($province == $city) {
        return $province ? $province : ($country ? $country : '不明');
      } else {
        $location = array_filter([$province, $city]);
        return $location ? $location : ($country ? $country : '不明');
      }

    } else {
      return $province ? $province : ($city ? $city : ($country ? $country : '不明')); 
    }
    
  } else if (($data['bogon'] ?? false) == true) { // ローカルネットワーク
    return 'ローカルネットワーク';

  } else {
    return '不明';
  }
}


// 代替位置サービス（海外対応）
function get_location_by_free($ip, $showcity) {
  $url = 'https://ip125.com/api/'.$ip.'?lang=zh-CN';
  $response = wp_remote_get($url, array('timeout' => 5));
  if (is_wp_error($response)) {
    return '不明';
  }
  $data = json_decode(wp_remote_retrieve_body($response), true);
  if (!is_array($data)) {
    return '不明';
  }
  $status = $data['status'] ?? '';
  $message = $data['message'] ?? '';

  if ($status == 'success') { // 成功
    $province = $data['regionName'] ?? '';
    $city = $data['city'] ?? '';
    $country = $data['country'] ?? '';
    $countryCode = $data['countryCode'] ?? '';

    if ($showcity) {
      if ($countryCode == 'CN') {
        $location = array_filter([$province, $city]);
        return $location ? $location : '中国';
      } else {
        $location = array_filter([$country, $city]);
        return $location ? $location : ($province ? $province : '不明');
      }

    } else {
      if ($countryCode == 'CN') {
        return $province ? $province : ($city ? $city : '中国');
      } else {
        return $country ? $country : ($city ? $city : ($province ? $province : '不明'));
      }
    }

  } else if ($message == 'private range' || $message == 'reserved range') { // ローカルネットワーク
    return 'ローカルネットワーク';

  } else {
    return '不明';
  }
}
