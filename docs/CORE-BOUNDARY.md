# CORE-BOUNDARY — PukiWiki2026 Core 作業境界

**ステータス:** 永続原則（2026-06-12）  
**関連:** [README.md](../README.md) · [PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md) · [UPGRADE.md](UPGRADE.md)

---

## 1. 原則

| 項目 | 内容 |
|------|------|
| Core ローカル | `D:\00_project\pukiwiki2026` |
| Plus ローカル | `D:\00_project\pukiwiki2026 Plus`（直接開発ワークスペース） |
| 本番 Core 設置先（例） | `/public_html/pukiwiki` |
| 本番 Plus 適用 | Plus リポジトリで Core 設置先を **手動上書き** |
| Plus エージェント | `D:\00_project\pukiwiki2026` へ **一切書き込みしない** |
| 作業範囲 | Plus リポジトリ内の `pukiwiki/`・`docs/` 等 |

---

## 2. してよいこと

- Plus リポジトリ内の `pukiwiki/` 直接開発
- GitHub 上の Core ドキュメント・ローカル Core の **読み取り** 参照
- `skin/` を参照し `skin2026/` で拡張

---

## 3. してはいけないこと

- `D:\00_project\pukiwiki2026\**` への write / patch / delete
- Core リポジトリへの commit / push
- `pukiwiki/skin/` の直接改変

---

## 4. handoff（Core 改修が必要な場合）

1. Plus エージェントは Core を触らない
2. `docs/handoff/` または Issue に handoff を残す
3. Core エージェントが PukiWiki2026 で PR
4. リリース後、Plus に取り込み

---

## 5. PR チェックリスト

- [ ] 変更はすべて Plus リポジトリ配下か
- [ ] `wiki/`・`cache/`・`attach/` 実データ・`pukiwiki.ini.php` をコミットしていないか
- [ ] セキュリティ変更は handoff へ回したか

---

## 6. skin2026 開発と本体改変の判断

`skin2026/` の UI 再設計（React シェル等）は **スキン配下のみ** を第一選択とする。

本体（`lib/`・`plugin/`・`index.php` 等）の改変が必要と判明したら、**実装を止め**、次のいずれかをユーザーに提示して承認を得る。

| 選択肢 | 内容 |
|--------|------|
| **A: skin のみで回避** | `pukiwiki.skin.php`・ビルド成果物・skin 内 JS/CSS で対応。本体は触らない |
| **B: Plus `pukiwiki/` を改変** | 本リポジトリの `pukiwiki/`（`skin2026/` 以外）をユーザー承認のうえ変更 |
| **C: Core へ handoff** | `D:\00_project\pukiwiki2026` は触らず、`docs/handoff/` または Issue で Core エージェントへ依頼 |

- エージェントは **黙って B や C を実行しない**。
- 判断待ちの依存は `docs/SKIN2026.md` の「要ユーザー判断」に記録する。

---

## 7. 決定ログ

| 日付 | 決定 |
|------|------|
| 2026-06-12 | Plus エージェントは Core ローカルを永久に改変しない |
| 2026-06-12 | 本番: Core 設置 → Plus 手動上書き。ユーザーデータはリポジトリから除外 |
| 2026-06-12 | skin2026 は skin のみ優先。本体改変はユーザー承認後のみ（§6） |
