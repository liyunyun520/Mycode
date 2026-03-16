<div class="item">
  <div class="field">
    <div class="title">
      <span>プライベートモード</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_private" name="oyiso_private" <?=get_option('oyiso_private') ? 'checked' : ''?>>
      <label for="oyiso_private" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとログインユーザーのみサイトを閲覧可能（ログインには影響なし）</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>サムネイル無効化</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_ban_thumbnail" name="oyiso_ban_thumbnail" <?=get_option('oyiso_ban_thumbnail') ? 'checked' : ''?>>
      <label for="oyiso_ban_thumbnail" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとメディアライブラリアップロード時にメイン画像のみ生成</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>又拍雲ストレージ</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_upyun_uss" name="oyiso_upyun_uss" <?=get_option('oyiso_upyun_uss') ? 'checked' : ''?>>
      <label for="oyiso_upyun_uss" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとメディアライブラリでクラウドストレージを使用</p>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>サービス名</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_upyun_bucketname" value="<?=get_option('oyiso_upyun_bucketname')?>">
      </div>
    </div>
    <p class="tip">クラウドストレージサービス名を入力（現在の使用量：<span id="upyun-usage">不明</span> ）</p>

    <div class="field">
      <div class="title">
        <span>アクセラレーションドメイン</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_upyun_host" value="<?=get_option('oyiso_upyun_host')?>">
      </div>
    </div>
    <p class="tip">形式：<code>http(s)://{カスタムアクセラレーションドメイン}</code>、例</p>
    <ul class="tip">
      <li><code>http(s)://upyun.oyiso.cn</code></li>
      <li><code>http(s)://upyun.oyiso.cn/path</code></li>
    </ul>
    <p class="tip">カスタムバインドドメインを使用、又拍雲デフォルトドメインは開発テスト用のみ</p>

    <div class="field">
      <div class="title">
        <span>オペレーターユーザー名</span>
      </div>
      <div class="text">
        <input type="password" name="oyiso_upyun_username" value="<?=get_option('oyiso_upyun_username')?>">
      </div>
    </div>
    <p class="tip">オペレーターユーザー名を入力</p>

    <div class="field">
      <div class="title">
        <span>オペレーター認証パスワード</span>
      </div>
      <div class="text">
        <input type="password" name="oyiso_upyun_passwd" value="<?=get_option('oyiso_upyun_passwd')?>">
      </div>
    </div>
    <p class="tip">認証オペレーターのパスワードを入力</p>
  </div>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>メールSMTP設定</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_smtp" name="oyiso_smtp" <?=get_option('oyiso_smtp') ? 'checked' : ''?>>
      <label for="oyiso_smtp" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">カスタムメールでメール送信</p>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>SMTPサーバーアドレス</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_smtp_host" value="<?=get_option('oyiso_smtp_host')?>">
      </div>
    </div>
    <p class="tip">SMTPサーバーアドレスを入力、例：QQのSMTPサーバーアドレス <code>smtp.qq.com</code></p>

    <div class="field">
      <div class="title">
        <span>SMTPサーバーポート</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_smtp_port" value="<?=get_option('oyiso_smtp_port')?>">
      </div>
    </div>
    <p class="tip">SMTPサーバーポートを入力、例 <code>465</code>、<code>587</code>、<code>25</code>、QQメールは通常 <code>465</code> または <code>25</code></p>

    <div class="field">
      <div class="title">
        <span>SMTPユーザー名</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_smtp_username" value="<?=get_option('oyiso_smtp_username')?>">
      </div>
    </div>
    <p class="tip">通常はメールアドレス、例 <code>user@example.com</code></p>

    <div class="field">
      <div class="title">
        <span>SMTPパスワード</span>
      </div>
      <div class="text">
        <input type="password" name="oyiso_smtp_password" value="<?=get_option('oyiso_smtp_password')?>">
      </div>
    </div>
    <p class="tip">SMTP設定パスワードを入力、QQメールはキー</p>

    <div class="field">
      <div class="title">
        <span>SMTP暗号化方式</span>
      </div>
      <div class="text">
        <div class="select">
          <input type="hidden" name="oyiso_smtp_secure" value="<?=get_option('oyiso_smtp_secure') ? get_option('oyiso_smtp_secure') : ''?>">
          <input type="text" class="hide" placeholder="暗号化方式を選択" readonly autocomplete="off"> 
          <div class="ul-box">
            <ul>
              <li data-value=''>暗号化方式を選択</li>
              <li data-value='ssl'>SSL暗号化</li>
              <li data-value='tls'>TLS暗号化</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <p class="tip">暗号化方式を選択、通常は <code>SSL</code> 暗号化方式</p>

    <div class="field">
      <div class="title">
        <span>サイト名</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_smtp_sitename" value="<?=get_option('oyiso_smtp_sitename')?>">
      </div>
    </div>
    <p class="tip">カスタムサイト名を入力、デフォルト：<code><?=get_bloginfo('name')?></code></p>

    <div class="field">
      <div class="title">
        <span>送信者名</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_smtp_fromname" value="<?=get_option('oyiso_smtp_fromname')?>">
      </div>
    </div>
    <p class="tip">カスタム送信者ニックネームを入力、デフォルト：<code><?php $display_name = get_userdata(1)->display_name; echo $display_name ? $display_name : get_bloginfo('name')?></code></p>
  </div>
</div>
