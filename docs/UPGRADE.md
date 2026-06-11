# UPGRADE — PukiWiki2026 Plus overlay 適用手順

稼働中の [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) に、PukiWiki2026 Plus の overlay を上書き適用する手順です。

**前提:** Plus は fork 一式の置き換えではありません。Core を先に設置し、差分ファイルのみを上書きします。

---

## 1. PukiWiki2026（Core）を設置する

1. [PukiWiki2026 リポジトリ](https://github.com/kuwa2005/PukiWiki2026) を clone または release をダウンロード
2. Web サーバー（PHP 8.x 推奨）のドキュメントルート、またはサブディレクトリに配置
3. [SETUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/SETUP.md) に従い `pukiwiki/pukiwiki.ini.php` を作成
4. ブラウザで Wiki が表示・編集できることを確認

インストールルートの目安:

```
/path/to/pukiwiki2026/
├── index.php
├── .htaccess
└── pukiwiki/
    ├── lib/
    ├── plugin/
    ├── skin/
    └── wiki/          … ページデータ（適用後も触らない）
```

---

## 2. バックアップを取得する

overlay 適用前に **必ず** バックアップしてください。

1. **ファイル:** インストールルート全体、または最低限 `pukiwiki/`（`wiki/`・`attach/` を含む）
2. **手順:** [PukiWiki2026 BACKUP.md](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/BACKUP.md) 参照
3. 本番の場合はメンテナンス表示またはアクセス制限を検討

Plus overlay は `wiki/`・`attach/`・`cache/` 等の **データディレクトリを変更しません** が、上書き対象の PHP/JS/CSS がある場合は復元用バックアップが必須です。

---

## 3. PukiWiki2026 Plus を取得する

```bash
git clone https://github.com/kuwa2005/PukiWiki2026-Plus.git
```

または GitHub の release アーカイブを展開します。

---

## 4. overlay を上書き適用する

`plus/` 配下のファイルが、対象インストールのルートへ **ミラー配置** でコピーされます。

例: `plus/pukiwiki-plus/skin/foo.js` → `{インストールルート}/pukiwiki-plus/skin/foo.js`

### Windows（PowerShell）

```powershell
cd PukiWiki2026-Plus\upgrade
.\apply.ps1 -TargetPath "C:\path\to\pukiwiki2026"
```

ドライラン（コピーせず一覧表示）:

```powershell
.\apply.ps1 -TargetPath "C:\path\to\pukiwiki2026" -WhatIf
```

### Linux / macOS

```bash
cd PukiWiki2026-Plus/upgrade
chmod +x apply.sh
./apply.sh /path/to/pukiwiki2026
```

ドライラン:

```bash
./apply.sh /path/to/pukiwiki2026 --dry-run
```

詳細・トラブルシューティング: [upgrade/README.md](../upgrade/README.md)

---

## 5. 適用後の確認

1. Web サーバー・PHP のキャッシュ（OPcache 等）をクリア
2. トップページ表示、編集、添付、アカウント操作を確認
3. 問題がある場合は **§2 のバックアップから復元**

将来、PukiWiki2026 側に `$pukiwiki2026_edition = 'plus'` 等の ini 設定が追加された場合は、[PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md) に従い有効化します（現時点では overlay ファイルの有無で機能が決まります）。

---

## 6. Plus 版のアップデート

1. **Core を先に更新** — [PukiWiki2026 DEPLOY.md §4.7](https://github.com/kuwa2005/PukiWiki2026/blob/main/pukiwiki/docs/DEPLOY.md) 参照
2. Plus リポジトリを最新に取得
3. **§2 バックアップ** のうえ **§4 を再実行**（同じ `-TargetPath`）
4. 動作確認

`wiki/` データはそのまま利用できます。

---

## 7. Plus を外す（Core のみに戻す）

1. overlay で追加された `pukiwiki-plus/` ディレクトリを削除
2. `plus/pukiwiki/` 経由で上書きした Core ファイルを、バックアップまたは PukiWiki2026 公式ファイルで復元
3. Plus 専用記法を使ったページがあれば内容を人手確認

---

## 関連リンク

| ドキュメント | 内容 |
|-------------|------|
| [PRODUCT-STRATEGY.md](PRODUCT-STRATEGY.md) | プロダクト方針・overlay 配置 |
| [plus/README.md](../plus/README.md) | overlay ファイルの置き方 |
| [PukiWiki2026 docs](https://github.com/kuwa2005/PukiWiki2026/tree/main/pukiwiki/docs) | Core の設計・デプロイ・セキュリティ |
