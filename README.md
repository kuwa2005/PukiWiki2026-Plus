# PukiWiki2026 Plus

**[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の Fork 版 — 直接開発・上書きデプロイ**（非公式）

PukiWiki2026 Plus は PukiWiki2026 をベースに UX 拡張（`skin/` の React シェル等）を同梱した **フルツリー** です。

## デプロイアーキテクチャ（確定）

| 層 | ローカル | 本番（例） |
|----|----------|------------|
| **Core（ベース）** | `D:\00_project\pukiwiki2026` | `/public_html/pukiwiki` |
| **Plus（上書き）** | `D:\00_project\pukiwiki2026 Plus` | Core 設置先を **手動上書き** |

**手順:** まず Core を本番へ設置・稼働確認 → Plus リポジトリの内容で上書き（[pukiwiki/docs/UPGRADE.md](pukiwiki/docs/UPGRADE.md)）。

| 原則 | 内容 |
|------|------|
| **Plus = 直接開発** | 本リポジトリの `pukiwiki/` を自由に改変する |
| **Core = 参照のみ** | ローカル `D:\00_project\pukiwiki2026` は **読み取り専用**。Plus エージェントは触らない |
| **データは上書きしない** | `wiki/`・`attach/`・`cache/`・`pukiwiki.ini.php` は git 管理外。上書き時も本番データを保護 |

## Core 作業境界

Plus 向け Cursor エージェントは `D:\00_project\pukiwiki2026` を改変しません。**[pukiwiki/docs/CORE-BOUNDARY.md](pukiwiki/docs/CORE-BOUNDARY.md)**

## リポジトリ構成

```
PukiWiki2026-Plus/
├── index.php
├── .htaccess
└── pukiwiki/          ← コード正本（データディレクトリは git 外）
    ├── docs/          ← Plus 向けドキュメント
    ├── lib/
    ├── plugin/
    ├── skin/          ← 既定スキン（React シェル + レガシー互換 JS/CSS）
    └── wiki/          ← .htaccess・index.html のみ
```

## クイックスタート（ローカル）

1. clone して Web サーバー（PHP 8.x）に配置
2. `pukiwiki/pukiwiki.ini.php.example` → `pukiwiki/pukiwiki.ini.php`
3. 既定 `SKIN_DIR` は `pukiwiki/skin/`（[pukiwiki/docs/SKIN-REACT.md](pukiwiki/docs/SKIN-REACT.md)）

## ドキュメント

索引: [pukiwiki/docs/README.md](pukiwiki/docs/README.md)

| 文書 | 内容 |
|------|------|
| [UPGRADE.md](pukiwiki/docs/UPGRADE.md) | Core 設置 → Plus 上書き（手動 FTP / rsync、`pukiwiki.ini.php` 除外） |
| [SKIN-REACT.md](pukiwiki/docs/SKIN-REACT.md) | 既定 React スキン（サイドバー、#head、編集ヘルプ） |
| [HEAD.md](pukiwiki/docs/HEAD.md) | `#head` ヒーロー画像プラグイン |
| [CORE-BOUNDARY.md](pukiwiki/docs/CORE-BOUNDARY.md) | Core 境界・handoff |
| [PRODUCT-STRATEGY.md](pukiwiki/docs/PRODUCT-STRATEGY.md) | プロダクト方針 |
| [CHANGELOG.md](CHANGELOG.md) | 変更履歴 |

## Git

| 項目 | 内容 |
|------|------|
| 作業フォルダ | `D:\00_project\pukiwiki2026 Plus` |
| `origin` | https://github.com/kuwa2005/PukiWiki2026-Plus.git |

`.env`・`pukiwiki/pukiwiki.ini.php`・`wiki/*.txt` 等は `.gitignore` で除外済み。

## ライセンス

GPL v2 以降（上流 PukiWiki / PukiWiki2026 に準拠）。
