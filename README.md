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

## バージョン管理（ローカル Git）

Plus は **PukiWiki2026 から独立したローカル Git リポジトリ** として管理します。Core 本体の改善は上流を参照し、Plus 固有の変更はこのリポジトリで commit します。

| 項目 | 内容 |
|------|------|
| 作業フォルダ | `D:\00_project\pukiwiki2026 Plus` |
| `origin`（Plus） | https://github.com/kuwa2005/PukiWiki2026-Plus.git |
| `upstream`（Core 参照） | https://github.com/kuwa2005/PukiWiki2026.git |

```powershell
cd "D:\00_project\pukiwiki2026 Plus"
git status
# 変更後（意味のある単位でこまめに commit）
git add …
git commit -m "説明"
# push は必要なときのみ（指示があるとき）
git push origin main
```

Core のセキュリティ修正等を取り込むときは `upstream` から fetch し、必要な差分だけ cherry-pick または merge してください（[pukiwiki/docs/PRODUCT-STRATEGY.md](pukiwiki/docs/PRODUCT-STRATEGY.md) 参照）。

`.env`・`pukiwiki/pukiwiki.ini.php`・`pukiwiki/wiki/`・`pukiwiki/cache/` 等は `.gitignore` で除外済みです。

## ライセンス

GPL v2 または（あなたの選択で）それ以降の GPL（上流 PukiWiki / PukiWiki2026 に準拠）。
