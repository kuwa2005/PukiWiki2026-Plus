# PRODUCT-STRATEGY — PukiWiki2026 Plus プロダクト方針

**ステータス:** 確定（2026-06-12）  
**関連:** [README.md](../../README.md) · [UPGRADE.md](UPGRADE.md) · [CHANGELOG.md](../../CHANGELOG.md)

---

## 0. 確定アーキテクチャ

| # | 原則 | 内容 |
|---|------|------|
| 1 | Fork | [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の拡張エディション |
| 2 | 開発 | `pukiwiki/` **フルツリー**を同梱する直接開発ワークスペース |
| 3 | 本番 | Core 設置 → **Plus 手動上書き**（`/public_html/pukiwiki`） |
| 4 | Core 境界 | ローカル `D:\00_project\pukiwiki2026` は参照のみ。Plus エージェントは改変しない |
| 5 | 上書き保護 | `wiki/`・`cache/`・`attach/` 等と `pukiwiki.ini.php` はリポジトリに含めない |

### パス対応

| 層 | ローカル | 本番（例） |
|----|----------|------------|
| Core | `D:\00_project\pukiwiki2026` | `/public_html/pukiwiki` |
| Plus | `D:\00_project\pukiwiki2026 Plus` | 同上を手動上書き |

---

## 1. 2 リポジトリ

| リポジトリ | semver | 役割 |
|-----------|--------|------|
| PukiWiki2026 | 1.x | セキュリティ・LTS |
| PukiWiki2026 Plus | 2.x | UX・React skin・実験 |

差分専用フォルダ方式は廃止し、フルツリー上書きデプロイに統一。

---

## 2. リポジトリに含めないもの

| パス | 理由 |
|------|------|
| `pukiwiki/wiki/*.txt` 等 | ページデータ |
| `pukiwiki/attach/*` | 添付 |
| `pukiwiki/cache/*` | キャッシュ |
| `pukiwiki/backup/*` | バックアップ出力 |
| `pukiwiki/diff/*` | diff 一時 |
| `pukiwiki/counter/*` | カウンタ |
| `pukiwiki/pukiwiki.ini.php` | 環境設定（`*.example` は同梱） |

各ディレクトリの `.htaccess`・`index.html` はリポジトリに含めます。

---

## 3. 機能配置

| 分類 | 配置 |
|------|------|
| 既定 skin（React） | `pukiwiki/skin/`（`src/`・`dist/`・互換 JS） |
| UX JS | `skin/` 内 |
| oEmbed 等 | `pukiwiki/lib/`・`plugin/` |
| セキュリティ | Core → handoff で Plus に取り込み |

---

## 4. フロー

新規・アップデートとも [UPGRADE.md](UPGRADE.md):

1. Core を本番に設置
2. バックアップ
3. Plus で手動上書き
4. スキン・表示確認（既定 `pukiwiki/skin/`）

---

## 5. 決定ログ

| 日付 | 決定 |
|------|------|
| 2026-06-12 | 上書きデプロイ用フルツリーを正式モデルとして確定 |
| 2026-06-12 | 上書きデプロイ用フルツリーを正式モデルに |
| 2026-06-12 | semver 1.x = Core、2.x = Plus |
