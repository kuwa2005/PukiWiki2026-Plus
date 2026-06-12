# Changelog — PukiWiki2026 Plus

本リポジトリ（PukiWiki2026 Plus）の変更履歴。  
Core の変更履歴は [PukiWiki2026/CHANGELOG.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/CHANGELOG.md) を参照。

## [Unreleased]

### Fixed

- **React スキン HTTP 500（PHP 8）** — `skin_app_build_config()` が `catbody()` ローカル変数を明示スコープで受け取るよう修正。`get_defined_vars()` 依存を廃止
- **skin2026 移行フォールバック** — `pkwk_resolve_skin_file()` / `pkwk_effective_skin_dir()` で削除済み `skin2026` パスを `pukiwiki/skin/` に自動退避
- **本番 ini 互換（PHP 8）** — 未定義の `$http_response_custom_headers` / `$nofollow` / `$html_meta_referrer_policy` で `pkwk_common_headers()` やスキンが TypeError にならないよう `init.php` と `html.php` でフォールバック
- **部分デプロイ耐性** — `pukiwiki.skin.php` に `pkwk_effective_skin_dir()` 等の互換スタブ、`json_encode` 失敗時 `{}` フォールバック

### Changed

- **skin2026 → skin 統合** — React シェルを `pukiwiki/skin/` に移行。既定 `SKIN_DIR` は `pukiwiki/skin/`。`docs/SKIN-REACT.md` 追加
- **CORE-BOUNDARY** — データ互換優先。Plus 内 `lib/`・`plugin/` 改変可（Core ローカルは不変）
- **デプロイアーキテクチャ確定** — Core 設置（`/public_html/pukiwiki`）→ Plus 手動上書き。README・PRODUCT-STRATEGY・UPGRADE・CORE-BOUNDARY を改訂
- **`.gitignore`** — `pukiwiki.ini.php`・`wiki/`・`cache/`・`attach/` 等を除外（上書きデプロイで本番データを保護）
- overlay 方式（`plus/`・`upgrade/`）を廃止しフルツリー上書きデプロイに統一

### Added

- **`pukiwiki/` フルツリー** — Core ベース＋React `skin/`
- **`docs/SKIN-REACT.md`** — 既定 React スキン手順
- **`pukiwiki/tools/diag-skin.php`** — レンタルサーバー向けスキン診断（token 保護）
- **`pukiwiki/lib/skin-diag-log.php`** — `cache/.skin-diag-enabled` で `cache/skin-error.log` に Fatal を記録
- **`pukiwiki/skin/minimal.fallback.skin.php`** — `cache/.skin-minimal-fallback` で React なし緊急表示

### Removed

- **`pukiwiki/skin2026/`** — `skin/` に統合
- **`docs/SKIN2026.md`** — `SKIN-REACT.md` に置換
- **`plus/`**・**`upgrade/`** — overlay 方式の廃止

---

## [0.1.0] - 2026-06-12

PukiWiki2026 Plus リポジトリ初期化。

---

## 記載ルール

- **Added** / **Changed** / **Fixed** / **Removed** / **Security**
