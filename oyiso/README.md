# Oyiso's Theme

![Version](https://img.shields.io/badge/Version-1.5.2-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-success.svg)
![License](https://img.shields.io/badge/License-GPL%20v2-orange.svg)

モダンで美しいWordPressブログテーマ。シンプルなデザインと豊富なカスタマイズ機能を備えた、日本語対応の高機能テーマです。

## 🌟 特徴

- **🎨 モダンなデザイン** - シンプルで洗練されたUI/UX
- **🌓 ダークモード対応** - 自動/手動切り替え可能
- **📱 レスポンシブ対応** - あらゆるデバイスに最適化
- **⚡ 高速パフォーマンス** - 最適化されたコードと遅延読み込み
- **🎭 カスタム投稿タイプ** - 一瞬（Moment）、ギャラリー対応
- **🌈 テーマカラー** - 自由にカスタマイズ可能

## ✨ 主な機能

### コンテンツ機能
- 📝 **記事投稿** - アイキャッチ画像、カテゴリー、タグ対応
- 📸 **ギャラリー** - 写真作品を美しく展示
- 💭 **一瞬（Moment）** - 短い思いや日常を共有
- 💬 **コメント** - リアルタイムコメント、絵文字対応
- 🔖 **友達リンク** - 申請・審査システム

### カスタマイズ機能
- 🎨 **テーマカラー設定** - プライマリ・セカンダリカラー
- 🖼️ **カバーアップロード** - カテゴリー別カバーアップロード
- 📢 **お知らせ通知** - ポップアップ通知機能
- 🔗 **ソーシャルリンク** - SNSアカウント表示
- ⚙️ **詳細設定パネル** - 直感的な管理画面

### 技術機能
- 🌙 **ダークモード** - 自動/手動切り替え
- 📊 **閲覧数カウント** - 人気記事表示
- 🗺️ **位置情報** - 投稿に位置情報を追加
- ☁️ **又拍雲ストレージ** - クラウドストレージ連携
- 📧 **メール通知** - SMTP設定・通知メール

## 📋 要件

| 項目 | バージョン |
|------|-----------|
| PHP | 8.0 以上 |
| WordPress | 5.0 以上 |
| MySQL | 5.5 以上 |

## 🚀 インストール

### 方法1: ZIPファイルからインストール

1. [リリースページ](https://github.com/username/oyiso/releases)から最新版をダウンロード
2. WordPress管理画面 → `外観` → `テーマ` → `新規追加` → `テーマのアップロード`
3. ダウンロードしたZIPファイルをアップロード
4. `有効化`をクリック

### 方法2: Gitからインストール

```bash
# WordPressのテーマディレクトリに移動
cd /path/to/wordpress/wp-content/themes/

# リポジトリをクローン
git clone https://github.com/username/oyiso.git

# テーマを有効化
```

## ⚙️ 設定

### 基本設定

1. WordPress管理画面 → `外観` → `Oyiso設定`
2. 各タブで設定を構成：
   - **基本設定** - サイトタイトル、ロゴ、ファビコン
   - **ホーム設定** - カバー画像、スローガン、レイアウト
   - **記事設定** - アイキャッチ、関連記事、シェアボタン
   - **コメント設定** - コメントルール、絵文字
   - **フッター設定** - 著作権、ICP情報
   - **その他** - カスタムコード、パフォーマンス

### カテゴリー設定

1. `投稿` → `カテゴリー` からカテゴリーを編集
2. カバーアップロードでカテゴリー画像を設定
3. カラー設定でテーマカラーをカスタマイズ

### メニュー設定

1. `外観` → `メニュー` からナビゲーションメニューを作成
2. 「トップメニュー」に割り当て
3. ソーシャルリンクは「ソーシャルメニュー」に設定

## 📁 ディレクトリ構造

```
oyiso/
├── admin/              # 管理画面設定
│   ├── option/         # 設定ページ
│   └── option.php      # 設定画面
├── assets/             # 静的リソース
│   ├── css/           # スタイルシート
│   ├── js/            # JavaScript
│   ├── images/        # 画像
│   └── iconfont/      # アイコンフォント
├── modules/            # 機能モジュール
│   ├── ajax/          # AJAX処理
│   └── *.php          # 各種機能
├── templates/          # ページテンプレート
│   ├── tpl-home.php   # ホームテンプレート
│   ├── tpl-gallery.php # ギャラリーテンプレート
│   └── ...
├── widgets/            # ウィジェット
├── plugins/            # 内蔵プラグイン
├── style.css          # テーマ情報
├── functions.php      # テーマ関数
└── index.php          # メインテンプレート
```

## 🎨 ページテンプレート

| テンプレート | 説明 |
|-------------|------|
| `tpl-home.php` | デフォルトホーム |
| `tpl-home1.php` | タブ付きホーム |
| `tpl-posts.php` | 投稿一覧 |
| `tpl-gallery.php` | ギャラリー |
| `tpl-moments.php` | 一瞬（Moment） |
| `tpl-archives.php` | アーカイブ |
| `tpl-categories.php` | カテゴリー一覧 |
| `tpl-tags.php` | タグ一覧 |
| `tpl-friends.php` | 友達リンク |
| `tpl-about.php` | 自己紹介 |

## 🔧 カスタマイズ

### 子テーマの作成

```php
<?php
// functions.php
add_action('wp_enqueue_scripts', 'child_theme_styles');
function child_theme_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
```

### カスタムCSS

管理画面 → `Oyiso設定` → `その他` → `カスタムCSS`

### カスタムJavaScript

管理画面 → `Oyiso設定` → `その他` → `カスタムJavaScript`

## 🤝 貢献

プロジェクトへの貢献を歓迎します！

### 開発に参加する

1. このリポジトリをフォーク
2. 機能ブランチを作成 (`git checkout -b feature/amazing-feature`)
3. 変更をコミット (`git commit -m 'Add amazing feature'`)
4. ブランチにプッシュ (`git push origin feature/amazing-feature`)
5. プルリクエストを作成

### バグ報告

バグを発見した場合は、[Issues](https://github.com/username/oyiso/issues)から報告してください。

### 機能リクエスト

新機能の提案も大歓迎です。Issuesで提案をお寄せください。

## 📝 更新履歴

### 1.5.2 (2025.06.07)
- テーマカスタムカラーを追加
- サイト通知ポップアップを追加
- コードブロックのハイライトスタイルを更新
- ホーム表示内容を調整
- 既知の問題を修正

### 1.5.1 (2025.01.19)
- 静的ファイルバージョン番号を追加
- 古いブラウザでのpjax問題を修正

### 1.5.0 (2024.12.08)
- 選択可能なホームテンプレートを追加
- 友達リンク承認メール通知を追加
- UI調整と問題修正

[すべての更新履歴を見る](CHANGELOG.md)

## 📄 ライセンス

このテーマは [GNU General Public License v2.0](http://www.gnu.org/licenses/gpl-2.0.html) の下で公開されています。

## 👤 作者

**Mystery Puppet Cat**

- ウェブサイト: [https://oyiso.cn](https://oyiso.cn)
- GitHub: [@username](https://github.com/username)

## 🙏 謝辞

- [WordPress](https://wordpress.org/) - CMSプラットフォーム
- [Fancybox](https://fancyapps.com/fancybox/) - ライトボックス
- [Prism.js](https://prismjs.com/) - シンタックスハイライト
- [ScrollReveal](https://scrollrevealjs.org/) - スクロールアニメーション
- [Iconfont](https://www.iconfont.cn/) - アイコン

---

**⭐ このプロジェクトが役立った場合は、スターをお願いします！**
