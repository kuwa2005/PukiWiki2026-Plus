# Changelog — PukiWiki2026 Plus

本リポジトリ（PukiWiki2026 Plus）の変更履歴。  
Core の変更履歴は [PukiWiki2026/CHANGELOG.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/CHANGELOG.md) を参照。

## [Unreleased]

### Added

- **`#head` プラグイン** — ブログ風ヒーロー画像（高さプリセット・WxH・auto）。React スキンではタイトル**上**にフルブリード表示。[HEAD.md](pukiwiki/docs/HEAD.md)
- **編集サイドバーヘルプ（EditSidebarHelp）** — 編集モードの左サイドバーに整形ルール早見表とよく使うプラグイン一覧。FormattingRules / ヘルプページへのリンク付き
- **編集フォームのプラグインマニュアルリンク** — 編集画面下部に FormattingRules と Plugin Manual へのリンクを復帰
- **`#ref` サイズ拡張** — `640x` / `x400` / ビューポート `%` 指定。[REF.md](pukiwiki/docs/REF.md)

### Changed

- **React スキン UI（2026-06-12 以降）**
  - サイドバーを **MENU のみ**に簡素化（ブランド行・ホーム・サイト名は維持）
  - 左下 **「.」リンク** — 未ログイン時はログイン、ログイン済みはログアウト（控えめ表示）
  - **リサイズ可能スプリッター** — デスクトップでサイドバー幅 200–400px（`localStorage: pukiwiki-skin-sidebar-width`）
  - **トップバー構成** — `#head` → ツールバー（ToolbarRow）→ タイトル → パンくず
  - **FrontPage パンくず** — トップページでも「Top」リンクを常時表示
  - **本文余白** — ヘッダー画像フルブリード、右ペイン余白統一、ツールバー非表示時の上部余白除去
  - **ライト/ダーク** — メイン領域横幅のずれを修正（スクロールバー幅の差異を吸収）
- **ToolbarRow 分離** — 編集ツールバーとログアウトを独立コンポーネント化
- **ドキュメント索引** — [pukiwiki/docs/README.md](pukiwiki/docs/README.md) を整備。各機能は SKIN-REACT / HEAD / UPGRADE へリンク

### Fixed

- **診断スクリプト 403** — `pukiwiki/.htaccess` の `RedirectMatch 403 ^/pukiwiki/tools` により旧 URL が拒否されていた。`diag-skin.php` を DocumentRoot 直下へ移動
- **React スキン HTTP 500（PHP 8）** — `skin_app_build_config()` が `catbody()` ローカル変数を明示スコープで受け取るよう修正。`get_defined_vars()` 依存を廃止
- **skin2026 移行フォールバック** — `pkwk_resolve_skin_file()` / `pkwk_effective_skin_dir()` で削除済み `skin2026` パスを `pukiwiki/skin/` に自動退避
- **本番 ini 互換（PHP 8）** — 未定義の `$http_response_custom_headers` / `$nofollow` / `$html_meta_referrer_policy` で `pkwk_common_headers()` やスキンが TypeError にならないよう `init.php` と `html.php` でフォールバック
- **部分デプロイ耐性** — `pukiwiki.skin.php` に `pkwk_effective_skin_dir()` 等の互換スタブ、`json_encode` 失敗時 `{}` フォールバック

### Changed（初期 Plus 化）

- **ドキュメント集約** — Plus 向け文書（CORE-BOUNDARY、PRODUCT-STRATEGY、SKIN-REACT、UPGRADE）を `pukiwiki/docs/` へ移動。root `docs/` はリダイレクト README のみ
- **skin2026 → skin 統合** — React シェルを `pukiwiki/skin/` に移行。既定 `SKIN_DIR` は `pukiwiki/skin/`。`pukiwiki/docs/SKIN-REACT.md` 追加
- **CORE-BOUNDARY** — データ互換優先。Plus 内 `lib/`・`plugin/` 改変可（Core ローカルは不変）
- **デプロイアーキテクチャ確定** — Core 設置（`/public_html/pukiwiki`）→ Plus 手動上書き。README・PRODUCT-STRATEGY・UPGRADE・CORE-BOUNDARY を改訂
- **`.gitignore`** — `pukiwiki.ini.php`・`wiki/`・`cache/`・`attach/` 等を除外（上書きデプロイで本番データを保護）
- overlay 方式（`plus/`・`upgrade/`）を廃止しフルツリー上書きデプロイに統一

### Added

- **`pukiwiki/` フルツリー** — Core ベース＋React `skin/`
- **`pukiwiki/docs/SKIN-REACT.md`** — 既定 React スキン手順
- **`diag-skin.php`**（DocumentRoot 直下）— レンタルサーバー向けスキン診断（token 保護）。`pukiwiki/.htaccess` が `tools/` を 403 にするためルート配置
- **`pukiwiki/lib/skin-diag.php`** — 診断本体（`diag-skin.php` から require）
- **`pukiwiki/lib/skin-diag-log.php`** — `cache/.skin-diag-enabled` で `cache/skin-error.log` に Fatal を記録
- **`pukiwiki/skin/minimal.fallback.skin.php`** — `cache/.skin-minimal-fallback` で React なし緊急表示

### Removed

- **`pukiwiki/skin2026/`** — `skin/` に統合
- **`docs/SKIN2026.md`** — `pukiwiki/docs/SKIN-REACT.md` に置換
- **`plus/`**・**`upgrade/`** — overlay 方式の廃止

---

## [0.1.0] - 2026-06-12

PukiWiki2026 Plus リポジトリ初期化。

---

## 記載ルール

- **Added** / **Changed** / **Fixed** / **Removed** / **Security**
