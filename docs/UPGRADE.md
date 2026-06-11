# UPGRADE — PukiWiki2026 Plus

Plus は **フルツリー直接開発** リポジトリです（overlay 廃止）。

## 新規設置

1. リポジトリを clone
2. Web サーバー（PHP 8.x）に配置
3. `pukiwiki.ini.php.example` を `pukiwiki.ini.php` にコピー
4. 任意で [SKIN2026.md](SKIN2026.md) に従い skin2026 を有効化

## アップデート

```powershell
cd "D:\00_project\pukiwiki2026 Plus"
git pull origin main
```

本番反映時は `wiki/`・`attach/`・`pukiwiki.ini.php` を上書きしないこと。
