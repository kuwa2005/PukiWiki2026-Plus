# upgrade/ — Plus overlay 適用スクリプト

PukiWiki2026 Plus の overlay ファイルを、稼働中の PukiWiki2026 インストールへコピーするスクリプト群です。

## 前提

- 対象は **PukiWiki2026 v1.x 以上** のインストール（`index.php` + `pukiwiki/` が存在すること）
- `wiki/`・`attach/`・`cache/` 等の **データディレクトリは変更しません**
- 適用前に **バックアップ** を取得してください（[PukiWiki2026 BACKUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/BACKUP.md) 参照）

## 使い方

### Windows（PowerShell）

```powershell
cd upgrade
.\apply.ps1 -TargetPath "C:\path\to\pukiwiki2026"
```

ドライラン（コピーせず対象ファイルを表示）:

```powershell
.\apply.ps1 -TargetPath "C:\path\to\pukiwiki2026" -WhatIf
```

### Linux / macOS

```bash
cd upgrade
chmod +x apply.sh
./apply.sh /path/to/pukiwiki2026
```

ドライラン:

```bash
./apply.sh /path/to/pukiwiki2026 --dry-run
```

## 動作

1. 対象パスに `index.php` と `pukiwiki/` が存在するか検証
2. `plus/` 配下の全ファイルを、対象インストールのルートへ **ミラー配置** でコピー
   - 例: `plus/pukiwiki-plus/skin/edit-dragdrop.js` → `{TargetPath}/pukiwiki-plus/skin/edit-dragdrop.js`
3. コピー結果を表示

## 注意事項

- **本番適用前にステージング環境でテスト** してください
- Plus overlay が空の場合（現状）、スクリプトは正常終了しますがコピー対象ファイルは README のみです
- Core の `pukiwiki/` ファイルを上書きする overlay がある場合、事前にバックアップを必ず取得してください
- 適用後は Web サーバーのキャッシュ（OPcache 等）をクリアすることを推奨します

## トラブルシューティング

| 症状 | 対処 |
|------|------|
| `index.php not found` | `-TargetPath` が PukiWiki2026 の **ルート**（`index.php` があるディレクトリ）を指しているか確認 |
| 権限エラー | Web サーバー実行ユーザーが書き込み可能な権限を付与 |
| 適用後に Wiki が動かない | バックアップから復元。Plus overlay の問題か Core の問題か切り分け |

## 関連

- [docs/UPGRADE.md](../docs/UPGRADE.md) — 設置から適用までの手順（ユーザー向け）
- [plus/README.md](../plus/README.md) — overlay ファイルの配置規則
- [docs/PRODUCT-STRATEGY.md](../docs/PRODUCT-STRATEGY.md) — プロダクト方針
- [PukiWiki2026 DEPLOY.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/DEPLOY.md) — Core のデプロイ手順
