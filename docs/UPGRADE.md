# UPGRADE — PukiWiki2026 Plus 設置・アップデート手順

本リポジトリは `index.php` と `pukiwiki/` を含む **デプロイ可能なフルツリー** です。

**本番の基本フロー:** まず [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026)（Core）を設置 → 稼働確認 → 本リポジトリの内容で **手動上書き** して Plus 化。

---

## 0. デプロイアーキテクチャ（確定）

| 層 | ローカル | 本番（例） |
|----|----------|------------|
| **Core（ベース）** | `D:\00_project\pukiwiki2026` | `/public_html/pukiwiki` |
| **Plus（上書き）** | `D:\00_project\pukiwiki2026 Plus` | Core 設置先を **手動上書き** |

Plus リポジトリには `wiki/`・`attach/`・`cache/` 実データと `pukiwiki.ini.php` を含めません（`.gitignore`）。上書きコピーしても本番のユーザーデータは消えません。

---

## 1. Core（PukiWiki2026）を設置する

1. [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) を clone または release を取得
2. 本番 `/public_html/pukiwiki`（または相当パス）に配置
3. [SETUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/SETUP.md) に従い `pukiwiki/pukiwiki.ini.php` を作成
4. ブラウザで表示・編集を確認

---

## 2. バックアップ（必須）

`wiki/`・`attach/`・`pukiwiki.ini.php` を必ず含める。[BACKUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/BACKUP.md) 参照。

---

## 3. Plus を取得する

```bash
git clone https://github.com/kuwa2005/PukiWiki2026-Plus.git
cd PukiWiki2026-Plus && git pull
```

---

## 4. Plus で本番を上書きする

**コピーする:** `index.php`、`.htaccess`、`pukiwiki/` のプログラム類（`skin2026/` 含む）。

**本番で上書きしない:** `pukiwiki.ini.php`、`wiki/**`（ページデータ）、`attach/**`（実ファイル）、`cache/**`、`backup/**`、`diff/**`、`counter/**`（実データ）。

### PowerShell 例

```powershell
$Source = "D:\00_project\pukiwiki2026 Plus"
$Target = "C:\path\to\public_html\pukiwiki"

Copy-Item "$Source\index.php","$Source\.htaccess" $Target -Force
$skip = @('wiki','attach','cache','backup','diff','counter','pukiwiki.ini.php')
Get-ChildItem "$Source\pukiwiki" -Force | Where-Object { $skip -notcontains $_.Name } |
  ForEach-Object { Copy-Item $_.FullName (Join-Path $Target 'pukiwiki' $_.Name) -Recurse -Force }
```

### rsync 例

```bash
rsync -av \
  --exclude 'pukiwiki/wiki/' --exclude 'pukiwiki/attach/' \
  --exclude 'pukiwiki/cache/' --exclude 'pukiwiki/backup/' \
  --exclude 'pukiwiki/diff/' --exclude 'pukiwiki/counter/' \
  --exclude 'pukiwiki/pukiwiki.ini.php' \
  /path/to/PukiWiki2026-Plus/ /path/to/public_html/pukiwiki/
```

---

## 5. skin2026 を有効化する（任意）

本番の `pukiwiki/pukiwiki.ini.php` に追記（ini はコピーしない）:

```php
define('SKIN_DIR', 'pukiwiki/skin2026/');
define('SKIN_FILE', DATA_HOME . 'skin2026/pukiwiki.skin.php');
```

詳細: [SKIN2026.md](SKIN2026.md)

---

## 6. 確認

OPcache クリア、表示・編集・添付を確認。skin2026 時は `pukiwiki/skin2026/pukiwiki.css` が **200** であること。

---

## 7. アップデート

1. Core を先に最新化（[DEPLOY.md §4.7](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/DEPLOY.md)）
2. Plus リポジトリを最新化
3. バックアップ → §4 を再実行

---

## 8. Plus を外す

バックアップまたは [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) から Core を復元。

---

## 9. ローカル開発

Plus リポジトリを Web サーバーに配置し、`pukiwiki.ini.php.example` から `pukiwiki.ini.php` を作成。Core ローカル（`D:\00_project\pukiwiki2026`）は読み取り参照のみ。

関連: [PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md) · [CORE-BOUNDARY.md](CORE-BOUNDARY.md)
