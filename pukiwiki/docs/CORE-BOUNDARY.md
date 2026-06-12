# CORE-BOUNDARY — PukiWiki2026 Core 作業境界

**ステータス:** 永続原則（2026-06-12 改訂）  
**関連:** [README.md](../../README.md) · [PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md) · [UPGRADE.md](UPGRADE.md)

---

## 1. 原則

| 項目 | 内容 |
|------|------|
| Core ローカル | `D:\00_project\pukiwiki2026` |
| Plus ローカル | `D:\00_project\pukiwiki2026 Plus`（直接開発ワークスペース） |
| 本番 Core 設置先（例） | `/public_html/pukiwiki` |
| 本番 Plus 適用 | Plus リポジトリで Core 設置先を **手動上書き** |
| Plus エージェント | `D:\00_project\pukiwiki2026` へ **一切書き込みしない** |
| 作業範囲 | Plus リポジトリ内の `pukiwiki/`・`pukiwiki/docs/` 等 |

---

## 2. してよいこと

- Plus リポジトリ内の `pukiwiki/` 直接開発（`skin/`・`lib/`・`plugin/` 含む）
- GitHub 上の Core ドキュメント・ローカル Core の **読み取り** 参照
- **データ互換優先:** wiki データ（`.txt`・添付）の互換を保つ範囲で Plus 内のプログラム改変を行う

---

## 3. してはいけないこと

- `D:\00_project\pukiwiki2026\**` への write / patch / delete
- Core リポジトリへの commit / push
- 本番 wiki 実データのリポジトリコミット

---

## 4. handoff（Core 改修が必要な場合）

1. Plus エージェントは Core を触らない
2. `pukiwiki/docs/handoff/` または Issue に handoff を残す
3. Core エージェントが PukiWiki2026 で PR
4. リリース後、Plus に取り込み

---

## 5. PR チェックリスト

- [ ] 変更はすべて Plus リポジトリ配下か
- [ ] `wiki/`・`cache/`・`attach/` 実データ・`pukiwiki.ini.php` をコミットしていないか
- [ ] wiki データ互換に影響する変更は文書化したか
- [ ] セキュリティ変更は handoff へ回したか

---

## 6. skin / 本体改変の判断

Plus 既定 UI は `pukiwiki/skin/`（React シェル）。**スキン配下** を第一選択とする。

`lib/`・`plugin/`・`index.php` 等の改変は、**wiki データ（`.txt`・添付）互換を保つ** ことを条件に Plus 内で実施してよい。UX とデータ互換はプログラム互換より優先。

Core ローカル（`D:\00_project\pukiwiki2026`）への変更が必要と判明した場合のみ handoff（§4）。

| 選択肢 | 内容 |
|--------|------|
| **A: skin のみ** | `pukiwiki.skin.php`・ビルド成果物・skin 内 JS/CSS で対応 |
| **B: Plus `pukiwiki/` 改変** | データ互換を保ちつつ本リポジトリの `pukiwiki/` を変更 |
| **C: Core へ handoff** | `D:\00_project\pukiwiki2026` は触らず、`pukiwiki/docs/handoff/` または Issue で依頼 |

---

## 7. 決定ログ

| 日付 | 決定 |
|------|------|
| 2026-06-12 | Plus エージェントは Core ローカルを永久に改変しない |
| 2026-06-12 | 本番: Core 設置 → Plus 手動上書き。ユーザーデータはリポジトリから除外 |
| 2026-06-12 | skin2026 は skin のみ優先。本体改変はユーザー承認後のみ（§6） |
| 2026-06-12 | **方針転換:** React スキンを `skin/` に統合。データ互換優先で Plus 内 `lib/`・`plugin/` 改変可。Core ローカルは不変 |
