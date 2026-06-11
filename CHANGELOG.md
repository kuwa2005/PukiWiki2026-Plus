# Changelog — PukiWiki2026 Plus

本リポジトリ（PukiWiki2026 Plus）における overlay パックの変更履歴。  
PukiWiki2026 Core の変更履歴は [PukiWiki2026/CHANGELOG.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/CHANGELOG.md) を参照してください。

形式は [Keep a Changelog](https://keepachangelog.com/ja/1.1.0/) に近い簡易版とします。

## [Unreleased]

### Changed

- **アーキテクチャドキュメントを overlay 型に統一** — README・PRODUCT-STRATEGY・UPGRADE を上書き適用モデルに改訂。Fork 版だが全ツリー同梱 fork ではない旨を明記
- **PRODUCT-STRATEGY.md** — リポジトリ root の `docs/` に配置。LEGACY `pukiwiki/` 移行計画（§2.2）を追記
- **`pukiwiki/docs/PRODUCT-STRATEGY.md`** — root `docs/` へのリダイレクト stub に差し替え

### Added

- **`docs/CORE-BOUNDARY.md`** — Core 作業境界（Plus エージェントは `D:\00_project\pukiwiki2026` を永久に改変しない・handoff 規則）
- **`.cursor/rules/pukiwiki2026-plus.mdc`** — Plus 向け Cursor 永続ルール
- **`docs/UPGRADE.md`** — Core 設置 → バックアップ → overlay 上書きの手順
- **`plus/`** — Plus overlay ファイルの受け皿（現時点は README のみ）
- **`upgrade/apply.ps1`** — Windows 向け overlay 適用スクリプト
- **`upgrade/apply.sh`** — Unix 向け overlay 適用スクリプト
- **`upgrade/README.md`** — 適用スクリプトの詳細・トラブルシューティング

---

## [0.1.0] - 2026-06-12

**PukiWiki2026 Plus リポジトリ初期化** — 旧構成（PukiWiki2026 全ツリー同梱）から overlay 型へ移行する前の区切り。

### Notes

- 当初は PukiWiki2026 を fork して全 `pukiwiki/` ツリーを同梱していたが、方針変更により overlay 型へ再編
- Plus 固有の overlay ファイルは未実装（今後 `plus/` に追加予定）

---

## 記載ルール（メモ）

- **Added** … 新機能・新 overlay ファイル
- **Changed** … 既存 overlay の変更
- **Fixed** … バグ修正
- **Removed** … 削除
- **Security** … セキュリティ関連

リリースタグを切る場合は `[x.y.z] - YYYY-MM-DD` の見出しを追加してください。
