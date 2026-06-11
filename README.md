# PukiWiki2026 Plus

**[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) を Fork した改造版**（非公式）

PukiWiki 1.5.4 UTF-8 ベースの PukiWiki2026 を土台に、Plus 向けに構成を整理した派生プロジェクトです。本体のセキュリティ強化・編集 UX 等は上流 [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) を参照してください。

| 項目 | 内容 |
|------|------|
| ベース | [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) |
| 作業フォルダ | `D:\00_project\pukiwiki2026 Plus` |
| 方針 | [pukiwiki/docs/PRODUCT-STRATEGY.md](pukiwiki/docs/PRODUCT-STRATEGY.md) |

## Plus での主な整理

- サンプル初期 wiki ページ・公式同梱ドキュメント／zip・開発用 tools・CI ワークフローをリポジトリから除外
- プロダクト方針を `pukiwiki/docs/PRODUCT-STRATEGY.md` に集約

## クイックスタート

1. Web サーバー（PHP 8.x 推奨）で本リポジトリを公開する。
2. `pukiwiki/pukiwiki.ini.php.example` から `pukiwiki/pukiwiki.ini.php` を用意して設定する。
3. `pukiwiki/wiki/`・`pukiwiki/cache/` 等に書き込み権限を付与する。
4. 詳細は上流の [pukiwiki/docs/SETUP.md](pukiwiki/docs/SETUP.md)・[pukiwiki/docs/DEPLOY.md](pukiwiki/docs/DEPLOY.md) を参照。

## ドキュメント

- [CHANGELOG.md](CHANGELOG.md)
- [pukiwiki/docs/](pukiwiki/docs/)（設計・デプロイ・編集 UX 等）

## ライセンス

GPL v2 または（あなたの選択で）それ以降の GPL（上流 PukiWiki / PukiWiki2026 に準拠）。
