# PukiWiki2026 Plus

**[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の Fork 版 — 直接開発ワークスペース**（非公式）

PukiWiki2026 Plus は PukiWiki2026 をベースにした **フルツリー同梱の開発用リポジトリ** です。`pukiwiki/` 配下を自由に編集し、Plus 固有の UX（`skin2026/` 等）をここで開発・検証します。

| 原則 | 内容 |
|------|------|
| **Plus = 直接開発** | 本リポジトリの `pukiwiki/` を自由に改変する |
| **Core = 参照のみ** | ローカル `D:\00_project\pukiwiki2026` は **読み取り専用参照**。Plus エージェントは触らない |
| **overlay 廃止** | 旧 `plus/`・`upgrade/` overlay モデルは **非推奨・削除済み** |

## Core 作業境界（エージェント・開発者向け）

**永続原則:** PukiWiki2026 Core のローカル作業ツリーは `D:\00_project\pukiwiki2026` ですが、**Plus 向け Cursor エージェントはそのパスへ一切書き込み・改変を行いません。**

| パス | 役割 | Plus エージェント |
|------|------|-------------------|
| `D:\00_project\pukiwiki2026` | Core 参照ツリー（読み取り専用） | **改変禁止** |
| `D:\00_project\pukiwiki2026 Plus` | Plus 直接開発ワークスペース | **ここだけで作業** |

- セキュリティ修正など Core 側が必要な変更は **handoff ドキュメント** で Core エージェント / [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) リポジトリへ委譲する。
- 詳細: **[docs/CORE-BOUNDARY.md](docs/CORE-BOUNDARY.md)** · Cursor ルール: `.cursor/rules/pukiwiki2026-plus.mdc`

| 項目 | 内容 |
|------|------|
| 作業フォルダ | `D:\00_project\pukiwiki2026 Plus` |
| 設置・アップデート | [docs/UPGRADE.md](docs/UPGRADE.md) |
| 方針 | [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md) |

## クイックスタート

1. 本リポジトリを clone する
2. Web サーバー（PHP 8.x 推奨）に配置
3. `pukiwiki/pukiwiki.ini.php.example` を `pukiwiki/pukiwiki.ini.php` にコピーして設定

skin2026: [docs/SKIN2026.md](docs/SKIN2026.md)

## ドキュメント

- [docs/CORE-BOUNDARY.md](docs/CORE-BOUNDARY.md)
- [docs/SKIN2026.md](docs/SKIN2026.md)
- [docs/UPGRADE.md](docs/UPGRADE.md)
- [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md)
