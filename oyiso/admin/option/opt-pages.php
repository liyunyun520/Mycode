<div class="item">
  <div class="field">
    <div class="title">
      <span>ナビゲーションバー アニメーション</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_navbar_animation" name="oyiso_navbar_animation" <?=get_option('oyiso_navbar_animation') ? 'checked' : ''?>>
      <label for="oyiso_navbar_animation" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとナビゲーションバーが上から下に表示されるアニメーション</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>画像遅延読み込み</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_lazyload" name="oyiso_lazyload" <?=get_option('oyiso_lazyload') ? 'checked' : ''?>>
      <label for="oyiso_lazyload" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にすると画像読み込み完了前にローディング画像を表示</p>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>プリセットローディング画像</span>
      </div>
      <div class="text">
        <div class="select">
          <input type="hidden" name="oyiso_lazyload_preset" value="<?=get_option('oyiso_lazyload_preset') ? get_option('oyiso_lazyload_preset') : '1'?>">
          <input type="text" class="hide" placeholder="プリセット1 - 小さなテレビ" readonly autocomplete="off"> 
          <div class="ul-box">
            <ul>
              <li data-value='1'>プリセット1 - 小さなテレビ</li>
              <li data-value='2'>プリセット2 - 回転する赤い水滴</li>
              <li data-value='3'>プリセット3 - 赤い小さな本</li>
              <li data-value='4'>プリセット4 - かわいい子猫</li>
              <li data-value='5'>プリセット5 - 青い星リング</li>
              <li data-value='6'>プリセット6 - Dribbbleバスケットボール</li>
              <li data-value='7'>プリセット7 - 五芒星</li>
              <li data-value='8'>プリセット8 - 変化する乗り物</li>
              <li data-value='9'>プリセット9 - 回り続ける</li>
              <li data-value='10'>プリセット10 - 青い円リング</li>
              <li data-value='11'>プリセット11 - 面白い子羊</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <p class="tip">テーマプリセットのローディング画像</p>

    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>カスタムローディング画像</span>
        </div>
        <div class="text">
          <input type="text" name="oyiso_lazyload_custom" value="<?=get_option('oyiso_lazyload_custom')?>">
        </div>
      </div>
      <p class="tip">ローディング画像URLを入力、カスタムローディング画像が優先表示</p>
    </div>
  </div>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>リンク自助申請</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_friend_sup" name="oyiso_friend_sup" <?=get_option('oyiso_friend_sup') ? 'checked' : ''?>>
      <label for="oyiso_friend_sup" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとユーザーはリンクページで相互リンクを申請可能</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>ページスムーズスクロール</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_smoothscroll" name="oyiso_smoothscroll" <?=get_option('oyiso_smoothscroll') ? 'checked' : ''?>>
      <label for="oyiso_smoothscroll" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとページスクロール時にバッファがかかりよりスムーズに（より多くのリソースを消費）</p>
</div>
