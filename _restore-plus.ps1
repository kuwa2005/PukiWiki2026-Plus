Remove-Item -Force ".git/index.lock" -ErrorAction SilentlyContinue
git fetch origin
git reset --hard origin/main
git checkout 48b8645 -- pukiwiki index.php .htaccess
git checkout a9093c2 -- pukiwiki/skin2026
git checkout 78a19b1 -- docs/CORE-BOUNDARY.md .cursor/rules/pukiwiki2026-plus.mdc
git rm -rf plus upgrade 2>$null

$readme = @"
# PukiWiki2026 Plus

**[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の Fork 版 — 直接開発ワークスペース**（非公式）

PukiWiki2026 Plus は PukiWiki2026 をベースにした **フルツリー同梱の開発用リポジトリ** です。``pukiwiki/`` 配下を自由に編集し、Plus 固有の UX（``skin2026/`` 等）をここで開発・検証します。

| 原則 | 内容 |
|------|------|
| **Plus = 直接開発** | 本リポジトリの ``pukiwiki/`` を自由に改変する |
| **Core = 参照のみ** | ローカル ``D:\00_project\pukiwiki2026`` は **読み取り専用参照**。Plus エージェントは触らない |
| **overlay 廃止** | 旧 ``plus/``・``upgrade/`` overlay モデルは **非推奨・削除済み** |

## Core 作業境界（エージェント・開発者向け）

**永続原則:** PukiWiki2026 Core のローカル作業ツリーは ``D:\00_project\pukiwiki2026`` ですが、**Plus 向け Cursor エージェントはそのパスへ一切書き込み・改変を行いません。**

| パス | 役割 | Plus エージェント |
|------|------|-------------------|
| ``D:\00_project\pukiwiki2026`` | Core 参照ツリー（読み取り専用） | **改変禁止** |
| ``D:\00_project\pukiwiki2026 Plus`` | Plus 直接開発ワークスペース | **ここだけで作業** |

- セキュリティ修正など Core 側が必要な変更は **handoff ドキュメント** で Core エージェント / [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) リポジトリへ委譲する。
- 詳細: **[docs/CORE-BOUNDARY.md](docs/CORE-BOUNDARY.md)** · Cursor ルール: ``.cursor/rules/pukiwiki2026-plus.mdc``

| 項目 | 内容 |
|------|------|
| 作業フォルダ | ``D:\00_project\pukiwiki2026 Plus`` |
| 設置・アップデート | [docs/UPGRADE.md](docs/UPGRADE.md) |
| 方針 | [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md) |

## リポジトリ構成

``````
PukiWiki2026-Plus/
├── index.php
├── .htaccess
├── docs/
└── pukiwiki/
    ├── skin/
    └── skin2026/
``````

## クイックスタート

1. 本リポジトリを clone する
2. Web サーバー（PHP 8.x 推奨）に配置
3. ``pukiwiki/pukiwiki.ini.php.example`` を ``pukiwiki/pukiwiki.ini.php`` にコピーして設定

skin2026: [docs/SKIN2026.md](docs/SKIN2026.md)

## ドキュメント

- [docs/CORE-BOUNDARY.md](docs/CORE-BOUNDARY.md)
- [docs/SKIN2026.md](docs/SKIN2026.md)
- [docs/UPGRADE.md](docs/UPGRADE.md)
- [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md)
"@
$readme = $readme -replace '``````','```'
Set-Content README.md -Value $readme -Encoding utf8NoBOM

Set-Content docs/PRODUCT-STRATEGY.md -Encoding utf8NoBOM -Value @"
# PRODUCT-STRATEGY — PukiWiki2026 Plus

**ステータス:** 確定（2026-06-12）

Plus リポジトリ（``D:\00_project\pukiwiki2026 Plus``）は **pukiwiki/ 全ツリー同梱の直接開発ワークスペース** です。旧 overlay（plus/・upgrade/）は **廃止・削除** しました。

Core（``D:\00_project\pukiwiki2026``）は読み取り参照のみ。詳細は [CORE-BOUNDARY.md](CORE-BOUNDARY.md) · [UPGRADE.md](UPGRADE.md) · [SKIN2026.md](SKIN2026.md)。
"@

Set-Content docs/UPGRADE.md -Encoding utf8NoBOM -Value @"
# UPGRADE — PukiWiki2026 Plus

Plus は **フルツリー直接開発** リポジトリです（overlay 廃止）。

## 新規設置

1. リポジトリを clone
2. Web サーバー（PHP 8.x）に配置
3. ``pukiwiki.ini.php.example`` を ``pukiwiki.ini.php`` にコピー
4. 任意で [SKIN2026.md](SKIN2026.md) に従い skin2026 を有効化

## アップデート

``````powershell
cd "D:\00_project\pukiwiki2026 Plus"
git pull origin main
``````

本番反映時は ``wiki/``・``attach/``・``pukiwiki.ini.php`` を上書きしないこと。
"@
(Get-Content docs/UPGRADE.md -Raw) -replace '``````','```' | Set-Content docs/UPGRADE.md -Encoding utf8NoBOM

Set-Content docs/SKIN2026.md -Encoding utf8NoBOM -Value @"
# skin2026

``````php
define('SKIN_DIR', 'pukiwiki/skin2026/');
define('SKIN_FILE', DATA_HOME . 'skin2026/pukiwiki.skin.php');
``````

``pukiwiki/skin/`` は変更しません。雛形は ``pukiwiki.ini.php.example``。
"@
(Get-Content docs/SKIN2026.md -Raw) -replace '``````','```' | Set-Content docs/SKIN2026.md -Encoding utf8NoBOM

$cb = Get-Content docs/CORE-BOUNDARY.md -Raw
$cb = $cb.Replace('Plus リポジトリ内のみ（`plus/`、`upgrade/`、`docs/` 等）','Plus リポジトリ内の `pukiwiki/`・`docs/` 等を **自由に編集**')
$cb = $cb.Replace('overlay、UX、メディア、実験機能、適用スクリプト','UX、`skin2026`、メディア、実験機能、`pukiwiki/` 直接開発')
$cb = $cb.Replace('Plus リポジトリに `pukiwiki/` 全ツリーを再同梱 | overlay 方針違反','`pukiwiki/skin/` の直接改変 | 標準スキンは触らず `skin2026/` で拡張する')
if ($cb -match '(?s)(.*?)\r?\n## 7\. overlay') { $cb = $Matches[1].TrimEnd() + "`n" }
Set-Content docs/CORE-BOUNDARY.md -Value $cb -Encoding utf8NoBOM

$mdc = Get-Content .cursor/rules/pukiwiki2026-plus.mdc -Raw
$mdc = $mdc.Replace('- `plus/` — overlay ファイル','- `pukiwiki/` — 直接開発（`skin/` は参照のみ、`skin2026/` で拡張）')
$mdc = $mdc.Replace("- `upgrade/` — 適用スクリプト（ユーザー明示の ``-TargetPath`` のみ。開発用 Core パス ``D:\00_project\pukiwiki2026`` を自律的に指定しない）`r`n",'')
Set-Content .cursor/rules/pukiwiki2026-plus.mdc -Value $mdc -Encoding utf8NoBOM

$iniPath = 'pukiwiki/pukiwiki.ini.php.example'
$lines = Get-Content $iniPath
$newLines = @()
foreach ($line in $lines) {
  $newLines += $line
  if ($line -match "define\('SKIN_DIR', 'pukiwiki/skin/'\);") {
    $newLines += '// PukiWiki2026 Plus — skin2026:'
    $newLines += "// define('SKIN_DIR', 'pukiwiki/skin2026/');"
    $newLines += "// define('SKIN_FILE', DATA_HOME . 'skin2026/pukiwiki.skin.php');"
    $newLines += '// 詳細: docs/SKIN2026.md'
  }
}
Set-Content $iniPath -Value $newLines -Encoding utf8NoBOM

$count = (git ls-files pukiwiki | Measure-Object).Count
Write-Host "PUKIWIKI_COUNT=$count"
if ($count -le 200) { throw "pukiwiki file count too low: $count" }

git add -A
git commit --trailer "Co-authored-by: Cursor <cursoragent@cursor.com>" -m "fix: pukiwiki/ を復元し Plus 直接開発モデルに統一"
git push origin main
Write-Host "COMMIT=$(git rev-parse HEAD)"
