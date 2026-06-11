# Changelog — PukiWiki2026 Plus

本リポジトリ（PukiWiki2026 Plus）の変更履歴。  
Core の変更履歴は [PukiWiki2026/CHANGELOG.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/CHANGELOG.md) を参照。

## [Unreleased]

### Changed

- **デプロイアーキテクチャ確定** — Core 設置（`/public_html/pukiwiki`）→ Plus 手動上書き。README・PRODUCT-STRATEGY・UPGRADE・CORE-BOUNDARY を改訂
- **`.gitignore`** — `pukiwiki.ini.php`・`wiki/`・`cache/`・`attach/` 等を除外（上書きデプロイで本番データを保護）
- overlay 方式（`plus/`・`upgrade/`）を廃止しフルツリー上書きデプロイに統一

### Added

- **`pukiwiki/` フルツリー** — Core ベース＋`skin2026/`
- **`docs/SKIN2026.md`** — skin2026 有効化手順

### Removed

- **`plus/`**・**`upgrade/`** — overlay 方式の廃止

---

## [0.1.0] - 2026-06-12

PukiWiki2026 Plus リポジトリ初期化。

---

## 記載ルール

- **Added** / **Changed** / **Fixed** / **Removed** / **Security**
