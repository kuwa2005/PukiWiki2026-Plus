# CORE-BOUNDARY — PukiWiki2026 Core 作業境界

**ステータス:** 永続原則（2026-06-12 確定）  
**対象:** Cursor エージェント、開発者、レビュアー  
**関連:** [README.md](../README.md) · [PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md)

---

## 1. 原則（要約）

| 項目 | 内容 |
|------|------|
| **Core ローカルパス** | `D:\00_project\pukiwiki2026`（[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) 作業ツリー） |
| **Plus ローカルパス** | `D:\00_project\pukiwiki2026 Plus`（本リポジトリ） |
| **Plus エージェントの禁止事項** | Core パスへの **一切の書き込み・改変・削除・新規作成** |
| **Plus エージェントの作業範囲** | Plus リポジトリ内の `pukiwiki/`・`docs/` 等を **自由に編集** |

> **PukiWiki2026 Plus 向け Cursor エージェントは、`D:\00_project\pukiwiki2026` を改変しない。** 参照が必要な場合は GitHub 上の上流ドキュメントまたはユーザー指示に従う。

---

## 2. なぜこの境界があるか

- Core と Plus は **別リポジトリ・別責務**（セキュリティ LTS vs UX overlay）
- Plus エージェントが Core ツリーを直接触ると、overlay 方針と矛盾し、誤コミットの温床になる
- ローカルに Core と Plus が隣接配置されていても、**エージェントの書き込み先は Plus のみ**

---

## 3. Plus エージェントがしてよいこと

| 操作 | 例 |
|------|-----|
| Plus リポジトリ内の編集 | `plus/` に overlay 追加、`docs/` 更新、`upgrade/` スクリプト修正 |
| ユーザー指定パスへの overlay **適用** | `upgrade/apply.ps1 -TargetPath "C:\deploy\wiki"` — **ユーザーが明示したインストール先**のみ |
| Core の **仕様・API の参照** | GitHub 上の [PukiWiki2026 ドキュメント](https://github.com/kuwa2005/PukiWiki2026/tree/main/pukiwiki/docs) を読む |
| ローカル検証用 clone | Plus リポジトリ内の `_local/`（`.gitignore` 対象）へ clone し、そこへ overlay 適用テスト |

---

## 4. Plus エージェントがしてはいけないこと

| 操作 | 理由 |
|------|------|
| `D:\00_project\pukiwiki2026\**` への write / patch / delete / 新規ファイル | Core 作業は Core エージェントの管轄 |
| Core リポジトリでの commit / push | Plus PR に Core 改変を混入させない |
| 「ついでに Core も直す」系の修正 | 境界違反。必ず handoff（§5）へ回す |
| Core パスを `-TargetPath` に指定して apply スクリプト実行 | 開発用 Core ツリーを overlay で汚染するリスク。ユーザー明示指示時のみ例外（エージェント自律判断では実行しない） |
| `pukiwiki/skin/` の直接改変 | 標準スキンは触らず `skin2026/` で拡張する |

---

## 5. セキュリティ修正・Core 改修の handoff

Plus 作業中に **Core 側の修正が必要** と判明した場合（セキュリティ hole、認証バグ、spamfilter、upstream 互換など）:

1. **Plus エージェントは Core を触らない**
2. **`docs/handoff/`**（将来作成）または Issue / ユーザーへの **handoff ドキュメント** を Plus リポジトリ内に残す
3. handoff には次を含める:
   - **現象・影響**（再現手順、CVE 番号があれば記載）
   - **提案パッチ**（diff またはファイルパス + 変更内容の説明 — Core リポジトリ向け）
   - **Plus 側への影響**（overlay 再適用要否、互換性）
4. **Core エージェント**（`D:\00_project\pukiwiki2026` ワークスペース）または人手が PukiWiki2026 リポジトリで PR
5. Core リリース後、Plus は overlay の再適用・CHANGELOG 追記のみ

handoff ファイル名の例: `docs/handoff/YYYY-MM-DD-core-<topic>.md`

---

## 6. Core エージェントとの役割分担

| 担当 | ワークスペース | 主な作業 |
|------|----------------|----------|
| **Core エージェント** | `D:\00_project\pukiwiki2026` | セキュリティ、認証、LTS、標準 plugin、DEPLOY |
| **Plus エージェント** | `D:\00_project\pukiwiki2026 Plus` | UX、`skin2026`、メディア、実験機能、`pukiwiki/` 直接開発 |

Plus 作業時は **本ファイルと `.cursor/rules/pukiwiki2026-plus.mdc` を優先** する。

---
