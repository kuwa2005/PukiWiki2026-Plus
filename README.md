# PukiWiki2026 Plus

**[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の Fork 版 — overlay を上書き適用する拡張パック**（非公式）

PukiWiki2026 Plus は PukiWiki2026 の Fork 版です。稼働中の PukiWiki2026 インストールに **差分ファイル（overlay）を上書きコピー** すると Plus 相当の環境になります。本リポジトリは **PukiWiki2026 全ツリーを改変して同梱する fork ではありません**（overlay のみ管理）。

| 原則 | 内容 |
|------|------|
| **Core は PukiWiki2026** | 本体の開発・セキュリティ保守は [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) のみ |
| **Plus は overlay のみ** | 本リポジトリは **PukiWiki2026 コードベースを直接改変しない** — Plus 固有ファイル・適用スクリプト・Plus 向けドキュメントのみ |
| **適用方式** | 既存インストールへ overlay を上書き（[docs/UPGRADE.md](docs/UPGRADE.md) 参照） |

| 項目 | 内容 |
|------|------|
| 前提 | [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) v1.x 以上を設置済み |
| 適用手順 | [docs/UPGRADE.md](docs/UPGRADE.md) |
| 方針 | [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md) |

## リポジトリ構成

```
PukiWiki2026-Plus/
├── README.md
├── CHANGELOG.md
├── docs/
│   ├── PRODUCT-STRATEGY.md
│   └── UPGRADE.md
├── plus/                  ← Plus overlay ファイル
│   └── pukiwiki-plus/     ← 推奨: Core と物理分離
└── upgrade/               ← overlay 適用スクリプト
    ├── apply.ps1
    └── apply.sh
```

## クイックスタート

1. **[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026)** を Web サーバー（PHP 8.x 推奨）に設置し、稼働を確認する
2. 本リポジトリを clone または release を取得する
3. **[docs/UPGRADE.md](docs/UPGRADE.md)** の手順どおりバックアップ → overlay 適用

## PukiWiki2026 との関係

| リポジトリ | 役割 |
|-----------|------|
| [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) | Core — セキュリティ強化・LTS トラック |
| **PukiWiki2026 Plus**（本リポ） | Plus — UX 拡張・メディア・実験機能の overlay パック |

- Plus 未適用 = PukiWiki2026 Core のみ
- Plus 適用後 = Core + Plus overlay

PukiWiki2026 本体の設計・デプロイ・セキュリティは上流 [pukiwiki/docs](https://github.com/kuwa2005/PukiWiki2026/tree/main/pukiwiki/docs) を参照してください。

## ドキュメント

- [docs/UPGRADE.md](docs/UPGRADE.md) — インストール・バックアップ・overlay 適用の手順
- [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md) — プロダクト方針（日本語）
- [CHANGELOG.md](CHANGELOG.md) — Plus 版の変更履歴
- [plus/README.md](plus/README.md) — overlay ファイルの配置規則
- [upgrade/README.md](upgrade/README.md) — 適用スクリプトの詳細

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
