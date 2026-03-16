<div class="item">
  <div class="field">
    <div class="title">
      <span>ログイン後コメント</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_comments_login" name="oyiso_comments_login" <?=get_option('oyiso_comments_login') ? 'checked' : ''?>>
      <label for="oyiso_comments_login" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとユーザーはログイン後にのみコメント可能</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>ページコメントエリア</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_comments_page" name="oyiso_comments_page" <?=get_option('oyiso_comments_page') ? 'checked' : ''?>>
      <label for="oyiso_comments_page" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとページでコメント機能を使用可能（ページはデフォルトで無効、ページ編集画面で個別に有効化が必要）</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>記事コメントエリア</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_comments_post" name="oyiso_comments_post" <?=get_option('oyiso_comments_post') ? 'checked' : ''?>>
      <label for="oyiso_comments_post" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にすると記事詳細ページでコメント機能を使用可能</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>瞬間コメントエリア</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_comments_moment" name="oyiso_comments_moment" <?=get_option('oyiso_comments_moment') ? 'checked' : ''?>>
      <label for="oyiso_comments_moment" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にすると瞬間ページでコメント機能を使用可能</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>IP帰属先</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_comments_ip" name="oyiso_comments_ip" <?=get_option('oyiso_comments_ip') ? 'checked' : ''?>>
      <label for="oyiso_comments_ip" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとコメントユーザーのIP帰属先を取得</p>

  <!-- 子項目 -->
  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>IP帰属先API</span>
      </div>
      <div class="text">
        <div class="select">
          <input type="hidden" name="oyiso_comments_ip_api" value="<?=get_option('oyiso_comments_ip_api') ? get_option('oyiso_comments_ip_api') : ''?>">
          <input type="text" class="hide" placeholder="APIを選択" readonly autocomplete="off"> 
          <div class="ul-box">
            <ul>
              <li data-value=''>APIを選択</li>
              <li data-value='tencent'>Tencent位置サービス（国内）</li>
              <li data-value='baidu'>Baiduスマート位置情報（国内）</li>
              <li data-value='ipinfo'>ipinfo.io（海外）</li>
              <li data-value='free'>無料API（世界）</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <p class="tip">ユーザーIP帰属先を取得するAPIを選択</p>
  </div>

  <!-- 子項目-子項目 -->
  <div class="select-sub">
    <div class="field">
      <div class="title">
        <span>IP帰属先APIキー</span>
      </div>
      <div class="text">
        <input type="password" name="oyiso_comments_ip_api_key" value="<?=get_option('oyiso_comments_ip_api_key')?>">
      </div>
    </div>
    <ul class="tip">
      <li>Tencent API：<code>WebServiceAPI</code>を選択し <code>Key</code> を入力（Tencent API+無料API方式）</li>
      <li>Baidu API：<code>サーバー側</code>を選択し <code>AK</code> を入力（Baidu API+無料API方式）</li>
      <li>ipinfo.io：ログイン登録後 <code>token</code> を入力（世界のIP帰属先を取得可能）</li>
      <li>無料API：キー入力不要、世界のIP帰属先を取得可能（取得速度が遅く、不安定）、上記3つから推奨</li>
    </ul>
  </div>
</div>
