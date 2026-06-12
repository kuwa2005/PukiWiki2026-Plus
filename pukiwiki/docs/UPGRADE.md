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
| 本番デプロイ先（DocumentRoot） | `/public_html/debugprint.com/pukiwiki/` |
| 本番 DATA_HOME | `/public_html/debugprint.com/pukiwiki/pukiwiki/` |
| 公開 URL | https://debugprint.com/ |

Plus リポジトリ全体（`index.php`・`.htaccess`・`pukiwiki/`）を **`/public_html/debugprint.com/pukiwiki/`** へフル上書きする。`/public_html/pukiwiki` ではない。

Apache の vhost DocumentRoot も上記デプロイ先（`…/debugprint.com/pukiwiki/`）を指すこと。`index.php` の `DATA_HOME` は `__DIR__ . '/pukiwiki/'` のため、配置後は `…/pukiwiki/pukiwiki/` が wiki 本体になる。Web 上の静的パス `pukiwiki/skin/dist/`（例: https://debugprint.com/pukiwiki/skin/dist/skin-app.js）はこの構成で正しい。

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
$Target = "\\server\public_html\debugprint.com\pukiwiki"   # DocumentRoot（index.php の置き場）

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
  /path/to/PukiWiki2026-Plus/ user@host:/public_html/debugprint.com/pukiwiki/
```

### FTP 自動デプロイ（WinSCP）

レンタルサーバーで FTP/FTPS が使える場合、リポジトリ同梱のスクリプトで §4 の除外ルール付き同期ができます。

**前提**

- [WinSCP](https://winscp.net/) を Windows にインストール（`WinSCP.com` を使用）
- 本番の `pukiwiki/pukiwiki.ini.php` は **READONLY**（パーミッション 444 等）のことが多い  
  → 転送しようとすると **失敗** するため、スクリプトは **常に ini を同期対象外** にします（ローカルに `pukiwiki.ini.php` があってもアップロードしません）
- `.env` に接続情報を書く（`.env.example` をコピー）

**手順**

```powershell
cd "D:\00_project\pukiwiki2026 Plus"
copy .env.example .env
# .env を編集: FTP_HOST, FTP_USER, FTP_PASSWORD, FTP_REMOTE_DIR 等
```

**DryRun（転送予定のみ・推奨の初回確認）**

```powershell
.\scripts\deploy-ftp.ps1 -DryRun
# または
deploy-ftp.bat -DryRun
```

**本番反映**

```powershell
.\scripts\deploy-ftp.ps1
# または
deploy-ftp.bat
```

| 項目 | 内容 |
|------|------|
| 同期元 | リポジトリルート（`index.php`・`.htaccess`・`pukiwiki/`） |
| 同期先 | `.env` の `FTP_REMOTE_DIR`（例: `public_html/debugprint.com/pukiwiki`） |
| **転送しない** | `pukiwiki/pukiwiki.ini.php`（必須除外）・`wiki/*.txt`・`attach/`・`cache/`・`backup/`・`diff/`・`counter/`・`.env`・`.git`・`node_modules`・`pukiwiki/skin/src/` 等 |
| **転送する** | `pukiwiki/skin/dist/`（ビルド済み JS/CSS）・`lib/`・`plugin/`・`skin/pukiwiki.skin.php` 等 |

`.env` の `FTP_EXCLUDE_INI=1`（既定）は「ini を上書きしない」方針の明示用です。`0` にしてもスクリプトは ini を転送しません。

WinSCP 未導入時はエラー終了します。**WinSCP のインストールを推奨**します。

反映後は §6（OPcache・HTTP 200 確認）を実行してください。

### HTTP 500 が出るとき（debugprint.com で確認済みのパターン）

| 症状 | 原因 | 対処 |
|------|------|------|
| `?cmd=rss` は **200**、トップ・閲覧は **500**（本文 0 バイト） | React スキン `skin_app_build_config()` が PHP 8 で `catbody()` ローカル変数を `global` 参照していた（`d9b1e97` 以前） | `pukiwiki/skin/pukiwiki.skin.php` と `pukiwiki/lib/html.php`・`func.php` を **`d9b1e97` 以降** の Plus で上書き（§4）→ OPcache クリア（§6） |
| CSS/JS は 200 だがページ 500 | 上記と同じ（静的ファイルは到達、PHP スキン描画のみ失敗）。`skin-app.js` の更新日時が古いままならコード未反映 | 同上 |
| 本番 ini に `skin2026` が残っている | 統合後 `skin2026/` は存在しない | **ini 全体を上書きせず** `SKIN_DIR` を `pukiwiki/skin/` に変更（§5）。`d9b1e97` 以降はコード側フォールバックあり |
| `skin.php` だけ更新し `func.php` を古いまま | `pkwk_effective_skin_dir()` 未定義で Fatal | `skin.php`・`html.php`・`func.php` をセットで上書き |
| 本番 ini が古く `$http_response_custom_headers` 未定義 | `pkwk_common_headers()` の `foreach` が PHP 8 TypeError → 500（本文 0 バイト） | `pukiwiki/lib/init.php`・`html.php` を最新 Plus で上書き（コード側で空配列フォールバック） |
| 診断で `syntax error, unexpected token "}"` in `pukiwiki.skin.php`（322 行付近） | ワンライン `if` 内の `skin_app_toolbar_hidden()` 呼び出し末尾に `;` がない（PHP 8 Parse error） | 最新の `pukiwiki/skin/pukiwiki.skin.php` を上書き → OPcache クリア（§6） |

### レンタルサーバー向けトラブルシュート（PHP エラーログなし）

本番で `?cmd=rss` は **200**、トップ・閲覧が **500**（レスポンス本文 0 バイト）のとき、原因はほぼ **React スキン描画（`catbody()` → `pukiwiki.skin.php`）** です。以下はサーバーの PHP エラーログが見られない場合の手順です。

#### 1. デプロイ先の確認（DocumentRoot）

| 項目 | 正しい例（debugprint.com） |
|------|---------------------------|
| DocumentRoot | `/public_html/debugprint.com/pukiwiki/`（`index.php` があるディレクトリ） |
| DATA_HOME（実体） | `…/pukiwiki/pukiwiki/` |
| 公開 URL | https://debugprint.com/ |
| 診断スクリプト URL | https://debugprint.com/diag-skin.php?token=plus-skin-diag-2026 |

`…/debugprint.com/` 直下に `index.php` を置く構成ではありません。Plus リポジトリ全体を **`…/debugprint.com/pukiwiki/`** へ上書きしてください（§0・§4）。

#### 2. 必須上書きファイル（セットで）

最低限、次を **同じタイミングで** 本番へ反映します。

- `index.php`
- `pukiwiki/lib/init.php`
- `pukiwiki/lib/html.php`
- `pukiwiki/lib/func.php`
- `pukiwiki/skin/pukiwiki.skin.php`
- `pukiwiki/skin/dist/`（ビルド済み JS/CSS）
- `diag-skin.php`（DocumentRoot 直下・診断用・後で削除可）
- `pukiwiki/lib/skin-diag.php`（診断本体）

反映後は OPcache をクリアするか、数分待ってから再確認します（§6）。

#### 3. 診断スクリプト `diag-skin.php`（推奨）

`pukiwiki/.htaccess` が `/pukiwiki/tools/` を **403** にするため、診断は **DocumentRoot 直下** の `diag-skin.php` を使います（`index.php` と同じ階層）。

1. 上記のとおり `diag-skin.php` と `pukiwiki/lib/skin-diag.php` をデプロイ
2. ブラウザで開く:  
   `https://debugprint.com/diag-skin.php?token=plus-skin-diag-2026`
3. 表の **NG** 行が原因候補（例: `function pkwk_effective_skin_dir` → `func.php` 未反映、`$http_response_custom_headers is array` → `init.php` 未反映、`full skin require() render` → スキン描画の Fatal）
| 診断で `syntax error, unexpected token "}"` in `pukiwiki.skin.php`（322 行付近） | ワンライン `if` 内の `skin_app_toolbar_hidden()` 呼び出し末尾に `;` がない（PHP 8 Parse error） | 最新の `pukiwiki/skin/pukiwiki.skin.php` を上書き → OPcache クリア（§6） |
4. 修正・再デプロイ後、**`diag-skin.php` をサーバーから削除**（`skin-diag.php` は残しても動作に影響しませんが、不要なら削除可）

#### 4. キャッシュログ（`skin-error.log`）

エラーログの代わりに、短時間だけファイルへ記録できます。

1. FTP/SSH で空ファイルを作成: `pukiwiki/cache/.skin-diag-enabled`（`cache/` は書き込み可であること）
2. 最新の `index.php` と `pukiwiki/lib/skin-diag-log.php` をデプロイ
3. 500 が出る URL（例: トップ）にアクセス
4. `pukiwiki/cache/skin-error.log` をダウンロードして `[FATAL]` 行を確認
5. 調査後、` .skin-diag-enabled` と `skin-error.log` を削除

#### 5. 緊急表示（React なしの最小スキン）

サイトを一時的に閲覧可能にするだけなら:

1. 空ファイルを作成: `pukiwiki/cache/.skin-minimal-fallback`
2. `pukiwiki/skin/minimal.fallback.skin.php` と `pukiwiki/lib/html.php` をデプロイ
3. トップが **200** になるか確認（見た目は簡素・React なし）
4. 本修正デプロイ後、`.skin-minimal-fallback` を削除

#### 6. `.htaccess` で一時的に `display_errors`（最終手段）

DocumentRoot（`…/debugprint.com/pukiwiki/.htaccess`）に **一時的に** 追加:

```apache
# DEBUG ONLY — 調査後必ず削除
php_flag display_errors On
php_value error_reporting 32767
```

500 のレスポンス本文に Fatal メッセージが出ます。**原因特定後は上記 2 行を削除**してください（本番では非表示が既定）。

---

## 5. スキン設定（本番 ini）

Plus の既定は React シェル付き `pukiwiki/skin/`。新規設置では `pukiwiki.ini.php.example` のとおり:

```php
define('SKIN_DIR', 'pukiwiki/skin/');
```

詳細: [SKIN-REACT.md](SKIN-REACT.md)

**React スキン UI（Plus）:** 左サイドバーは MENU プラグイン（Hot! / PickUp 等）のみ表示。検索・ログイン・ブランド見出しはサイドバーから除去。デスクトップではサイドバー幅をドラッグで 200〜400px に調整でき、`localStorage`（`pukiwiki-skin-sidebar-width`）に保存される。反映には `pukiwiki/skin/` で `npm run build` 後、`skin/dist/` を本番へ同期すること。

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
