# UPGRADE — PukiWiki2026 Plus 設置・アップデート手順

本リポジトリは `index.php` と `pukiwiki/` を含む **デプロイ可能なフルツリー** です。

**本番の基本フロー:** まず [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026)（Core）を設置 → 稼働確認 → 本リポジトリの内容で **手動上書き** して Plus 化。

---

## 0. デプロイアーキテクチャ（確定）

| 層 | ローカル | 本番（例） |
|----|----------|------------|
| **Core（ベース）** | `D:\00_project\pukiwiki2026` | `/public_html/pukiwiki` |
| **Plus（上書き）** | `D:\00_project\pukiwiki2026 Plus` | Core 設置先を **手動上書き** |

**実運用例（debugprint.com）**

| 項目 | パス |
|------|------|
| ローカル Plus | `D:\00_project\pukiwiki2026 Plus` |
| 本番 DocumentRoot | `/public_html/debugprint.com/`（`index.php` の位置） |
| 本番 DATA_HOME | `/public_html/debugprint.com/pukiwiki/` |
| 公開 URL | https://debugprint.com/ |

`index.php` は DocumentRoot 直下、`pukiwiki/` はその子ディレクトリ（`DATA_HOME`）です。リポジトリをサーバーへコピーするときは **DocumentRoot へ `index.php` と `.htaccess`、子に `pukiwiki/`** という構成を維持してください。

旧 Core 設置先を Plus で **フル上書き** する想定。Plus リポジトリには `wiki/`・`attach/`・`cache/` 実データと `pukiwiki.ini.php` を含めません（`.gitignore`）。除外付きコピーなら本番のユーザーデータは消えません。

**重要: 本番 `pukiwiki.ini.php` は上書きしない。** ローカルに `pukiwiki.ini.php` があっても git 管理外であり、誤って本番 ini を開発用・雛形で置き換えると認証・`SKIN_DIR` 等が壊れます。Plus 反映後に必要ならサーバー上で `SKIN_DIR` だけ `pukiwiki/skin/` に直してください（§5）。

---

## 1. Core（PukiWiki2026）を設置する

1. [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) を clone または release を取得
2. 本番 `/public_html/pukiwiki`（または `/public_html/debugprint.com/pukiwiki` 等）に配置
3. [SETUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/SETUP.md) に従い `pukiwiki/pukiwiki.ini.php` を作成
4. ブラウザで表示・編集を確認

---

## 2. バックアップ（必須）

`wiki/`・`attach/`・`pukiwiki.ini.php` を必ず含める。[BACKUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/BACKUP.md) 参照。

---

## 3. Plus を取得する（ローカル）

本番へコピーする前に、ローカル Plus リポジトリを最新化する。

```powershell
cd "D:\00_project\pukiwiki2026 Plus"
git pull
```

初回 clone の場合:

```bash
git clone https://github.com/kuwa2005/PukiWiki2026-Plus.git
cd PukiWiki2026-Plus && git pull
```

---

## 4. Plus で本番を上書きする

### 上書きの原則

| 区分 | パス | 扱い |
|------|------|------|
| **上書きしない** | `pukiwiki/pukiwiki.ini.php` | 本番 ini を維持（サーバー上で必要なら部分編集のみ） |
| **上書きしない** | `pukiwiki/wiki/*.txt` | ページデータ |
| **上書きしない** | `pukiwiki/attach/*` | 添付ファイル |
| **上書きしない** | `pukiwiki/cache/*` | キャッシュ |
| **上書きしない** | `pukiwiki/backup/**` `diff/**` `counter/**` | 実データ |
| **上書きする** | `index.php` `.htaccess` | ルート |
| **上書きする** | `pukiwiki/skin/pukiwiki.skin.php` | React スキン（`391fdce` 以降の 500 修正を含む） |
| **上書きする** | `pukiwiki/skin/dist/` | ビルド済み CSS/JS |
| **上書きする** | `pukiwiki/lib/` `pukiwiki/plugin/` 等 | プログラム類 |

**コピーする:** `index.php`、`.htaccess`、`pukiwiki/` のプログラム類（`skin/pukiwiki.skin.php`・`skin/dist/` 含む）。

**本番で上書きしない:** `pukiwiki.ini.php`、`wiki/**`（ページデータ）、`attach/**`（実ファイル）、`cache/**`、`backup/**`、`diff/**`、`counter/**`（実データ）。

### PowerShell 例（debugprint.com）

```powershell
$Source = "D:\00_project\pukiwiki2026 Plus"
$Target = "\\server\public_html\debugprint.com"   # DocumentRoot（index.php の置き場）

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
  /path/to/PukiWiki2026-Plus/ user@host:/public_html/debugprint.com/
```

### HTTP 500 が出るとき（debugprint.com で確認済みのパターン）

| 症状 | 原因 | 対処 |
|------|------|------|
| `?cmd=rss` は **200**、トップ・閲覧は **500**（本文 0 バイト） | React スキン `skin_app_build_config()` が PHP 8 で `catbody()` ローカル変数を `global` 参照していた（`391fdce` 以前） | `pukiwiki/skin/pukiwiki.skin.php` と `pukiwiki/lib/html.php`・`func.php` を最新 Plus で上書き（§4） |
| CSS は 200 だがページ 500 | 上記と同じ（静的ファイルは到達、PHP スキン描画のみ失敗） | 同上 |
| 本番 ini に `skin2026` が残っている | 統合後 `skin2026/` は存在しない | **ini 全体を上書きせず** `SKIN_DIR` を `pukiwiki/skin/` に変更（§5）。コード側でも `skin2026` 参照はフォールバックあり |

---

## 5. スキン設定（本番 ini）

Plus の既定は React シェル付き `pukiwiki/skin/`。新規設置では `pukiwiki.ini.php.example` のとおり:

```php
define('SKIN_DIR', 'pukiwiki/skin/');
```

詳細: [SKIN-REACT.md](SKIN-REACT.md)

**既存本番:** 旧 ini で `skin2026` を指定している場合は、**ini ファイル全体を開発環境から上書きせず**、サーバー上の `pukiwiki.ini.php` を編集して `SKIN_DIR` を `pukiwiki/skin/` に変更する。

---

## 6. 確認

1. OPcache をクリア（利用中の場合）
2. トップページが **HTTP 200** であること  
   - 例: https://debugprint.com/
3. RSS が **HTTP 200** であること  
   - 例: https://debugprint.com/?cmd=rss
4. `pukiwiki/skin/dist/skin-app.css` と `skin-app.js` が **200** であること
5. 表示・編集・添付を目視確認

---

## 7. アップデート

1. Core を先に最新化（[DEPLOY.md §4.7](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/DEPLOY.md)）— Core 単体更新時のみ
2. **ローカル** Plus リポジトリで `git pull`（§3）
3. バックアップ → §4 を再実行（本番へコピー）
4. §6 の HTTP 200 確認

---

## 8. Plus を外す

バックアップまたは [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) から Core を復元。

---

## 9. ローカル開発

Plus リポジトリを Web サーバーに配置し、`pukiwiki.ini.php.example` から `pukiwiki.ini.php` を作成。Core ローカル（`D:\00_project\pukiwiki2026`）は読み取り参照のみ。

関連: [PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md) · [CORE-BOUNDARY.md](CORE-BOUNDARY.md)
