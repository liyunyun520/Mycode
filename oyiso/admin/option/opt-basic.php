<?php
  $theme_color = get_option('oyiso_theme_color');

  $theme_colors = get_option('oyiso_theme_colors');

  $theme_colors_custom = get_option('oyiso_theme_colors_custom');

  $pri = '#8183ff';
  $sub = '#67c3d3';
  
  if ($theme_colors_custom) {
    $pri = $theme_colors_custom->pri;
    $sub = $theme_colors_custom->sub;
  }
?>

<div class="item">
  <div class="field color-select">
    <div class="title">
      <span>デフォルトテーマカラー</span>
    </div>
    <div class="color-wrap text">
      <div class="radio-list">
        <div class="radio-item theme_color">
          <input type="radio" class="color" name="oyiso_theme_color" id="purple" value="" <?=$theme_color == '' ? 'checked' : ''?>>
          <label for="purple" data-color-pri="#8183ff" data-color-sub="#67c3d3"></label>
          <p>ブルーパープル</p>
        </div>
        <div class="radio-item theme_color">
          <input type="radio" class="color" name="oyiso_theme_color" id="green" value="green" <?=$theme_color == 'green' ? 'checked' : ''?>>
          <label for="green" data-color-pri="#7ea774" data-color-sub="#e7b172"></label>
          <p>秋葉グリーン</p>
        </div>
        <div class="radio-item theme_color">
          <input type="radio" class="color" name="oyiso_theme_color" id="orange" value="orange" <?=$theme_color == 'orange' ? 'checked' : ''?>>
          <label for="orange" data-color-pri="#e88a23" data-color-sub="#cf4141"></label>
          <p>マンゴーオレンジ</p>
        </div>
        <div class="radio-item theme_color">
          <input type="radio" class="color" name="oyiso_theme_color" id="blue" value="blue" <?=$theme_color == 'blue' ? 'checked' : ''?>>
          <label for="blue" data-color-pri="#009cd4" data-color-sub="#84dfff"></label>
          <p>ビリビリ - ブルー</p>
        </div>
        <div class="radio-item theme_color">
          <input type="radio" class="color" name="oyiso_theme_color" id="pink" value="pink" <?=$theme_color == 'pink' ? 'checked' : ''?>>
          <label for="pink" data-color-pri="#ed667e" data-color-sub="#ffc0cb"></label>
          <p>ビリビリ - ピンク</p>
        </div>
        <div class="radio-item theme_color">
          <input type="radio" class="color" name="oyiso_theme_color" id="custom" value="custom" <?=$theme_color == 'custom' ? 'checked' : ''?>>
          <label for="custom" data-color-pri="<?=$pri?>" data-color-sub="<?=$sub?>"></label>
          <p>カスタム</p>
        </div>
      </div>
    </div>
  </div>

  <div class="item-sub" id="custom-color-panel">
    <div class="field">
      <div class="title">
        <span>カラーピッカー <i>New-1.5.2</i></span>
      </div>
      <div class="text">
        <label for="custom_color_pri">
          <input type="color" class="color-pri" id="custom_color_pri" name="oyiso_custom_color_pri" value="<?=$pri?>">
        </label>
        <label for="custom_color_sub">
          <input type="color" class="color-sub" id="custom_color_sub" name="oyiso_custom_color_sub" value="<?=$sub?>">
        </label>
      </div>
    </div>
    <p class="tip">それぞれメインカラーとサブカラーです（グラデーション不要なら同じ色に設定）</p>
  </div>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>カラー同期スペース</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_cover_color" name="oyiso_cover_color" <?=get_option('oyiso_cover_color') ? 'checked' : ''?>>
      <label for="oyiso_cover_color" class="toggleSwitch"></label>
    </div>
  </div>
  <ul class="tip">
    <li>有効にすると全サイトのテーマカラーとカバー画像の色調を同期</li>
    <li>カバー画像が検出されない、または色が不明瞭な場合はデフォルトのテーマカラーを使用</li>
    <li>Tip：記事の公開または更新時のみ色抽出がトリガーされます</li>
  </ul>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>カスタムカラー抽出カバー画像</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_cover_color_url" value="<?=get_option('oyiso_cover_color_url')?>">
      </div>
    </div>
    <p class="tip">カバー画像のURLを入力（記事とページのみ有効、ランダム画像API対応、ただしカバーとカラーの同期がずれる可能性があるため非推奨）</p>
  </div>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>ソーシャルリンクチュートリアル</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_social" name="oyiso_social" <?=get_option('oyiso_social') ? 'checked' : ''?>>
      <label for="oyiso_social" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとソーシャルアイコンを表示</p>

  <!-- 子項目 -->
  <div class="item-sub">
    <p class="tip"><b>ソーシャルアイコンの使い方：</b></p>
    <p class="tip">外観 - メニュー - 表示オプション（右上）、<code>CSSクラス</code> にチェック（その他は任意）</p>
    <p class="tip">最初のアイコンにはテキスト追加可能、アイコンの後に <code>span</code> タグを追加</p>
    <p class="tip">具体的な使用方法（カスタムリンクを追加）：</p>
    <ul class="tip">
      <li>URL：<code>https://github.com/kannafay</code></li>
      <li>リンクテキスト / ナビゲーションタグ：<code>&lt;i class="iconfont icon-github-fill"&gt;&lt;/i&gt;&lt;span&gt;My GitHub&lt;/span&gt;</code></li>
      <li>title属性：<code>GitHub</code>（任意）</li>
      <li>CSSクラス：<code>github</code></li>
    </ul>
    <br>
    <p class="tip"><b>ソーシャルアイコン（全25種）：</b></p>
    <ul class="tip">
      <li><i class="iconfont icon-github-fill"></i> GitHub：<code>&lt;i class="iconfont icon-github-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>github</code></li>
      <li><i class="iconfont icon-gitee-fill"></i> Gitee：<code>&lt;i class="iconfont icon-gitee-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>gitee</code></li>
      <li><i class="iconfont icon-bilibili-fill"></i> ビリビリ：<code>&lt;i class="iconfont icon-bilibili-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>bili</code>（ピンク）/  <code>bili-b</code>（ブルー）</li>
      <li><i class="iconfont icon-qq-fill"></i> QQ：<code>&lt;i class="iconfont icon-qq-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>qq</code></li>
      <li><i class="iconfont icon-wechat-fill"></i> WeChat：<code>&lt;i class="iconfont icon-wechat-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>wechat</code></li>
      <li><i class="iconfont icon-netease-fill"></i> NetEase Cloud Music：<code>&lt;i class="iconfont icon-netease-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>netease</code></li>
      <li><i class="iconfont icon-weibo-fill"></i> Weibo：<code>&lt;i class="iconfont icon-weibo-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>weibo</code></li>
      <li><i class="iconfont icon-douyin-fill"></i> Douyin：<code>&lt;i class="iconfont icon-douyin-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>douyin</code></li>
      <li><i class="iconfont icon-kuaishou-fill"></i> Kuaishou：<code>&lt;i class="iconfont icon-kuaishou-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>kuaishou</code></li>
      <li><i class="iconfont icon-tieba-fill"></i> Baidu Tieba：<code>&lt;i class="iconfont icon-tieba-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>tieba</code></li>
      <li><i class="iconfont icon-zhihu-fill"></i> Zhihu：<code>&lt;i class="iconfont icon-zhihu-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>zhihu</code></li>
      <li><i class="iconfont icon-redbook-fill"></i> Xiaohongshu：<code>&lt;i class="iconfont icon-redbook-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>redbook</code></li>
      <li><i class="iconfont icon-jianshu-fill"></i> Jianshu：<code>&lt;i class="iconfont icon-jianshu-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>jianshu</code></li>
      <li><i class="iconfont icon-douban-fill"></i> Douban：<code>&lt;i class="iconfont icon-douban-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>douban</code></li>
      <li><i class="iconfont icon-youtube-fill"></i> Youtube：<code>&lt;i class="iconfont icon-youtube-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>yb</code></li>
      <li><i class="iconfont icon-telegram-fill"></i> Telegram：<code>&lt;i class="iconfont icon-telegram-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>tg</code></li>
      <li><i class="iconfont icon-instagram-fill"></i> Instagram：<code>&lt;i class="iconfont icon-instagram-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>ins</code></li>
      <li><i class="iconfont icon-twitter-fill"></i> Twitter：<code>&lt;i class="iconfont icon-twitter-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>twitter</code></li>
      <li><i class="iconfont icon-facebook-box-fill"></i> Facebook：<code>&lt;i class="iconfont icon-facebook-box-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>fb</code></li>
      <li><i class="iconfont icon-linkedin-box-fill"></i> Linkedin：<code>&lt;i class="iconfont icon-linkedin-box-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>in</code></li>
      <li><i class="iconfont icon-twitch-fill"></i> Twitch：<code>&lt;i class="iconfont icon-twitch-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>twitch</code></li>
      <li><i class="iconfont icon-messenger-fill"></i> Messenger：<code>&lt;i class="iconfont icon-messenger-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>mess</code></li>
      <li><i class="iconfont icon-dribbble-fill"></i> Dribbble：<code>&lt;i class="iconfont icon-dribbble-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>dribbble</code></li>
      <li><i class="iconfont icon-at-fill"></i> Email：<code>&lt;i class="iconfont icon-at-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>email</code></li>
      <li><i class="iconfont icon-links-fill"></i> カスタムリンク：<code>&lt;i class="iconfont icon-links-fill"&gt;&lt;/i&gt;</code> CSSクラス：<code>link</code></li>
    </ul>
  </div>
</div>
