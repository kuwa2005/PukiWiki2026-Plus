# HEAD — ヒーロー画像（`#head`）

ブログ風のページ先頭ヘッダー画像を、メインコンテンツ幅いっぱいに表示する Plus 向けプラグイン。

## 記法

```text
#head(hero.jpg)
#head(hero.jpg,180)
```

| 引数 | 説明 |
|------|------|
| 1 | 添付画像ファイル名（現在ページ、または `別ページ/ファイル名`） |
| 2 | 省略可。固定高さ（px）。指定時は `object-fit: cover` で中央トリミング |

インライン: `&head(hero.jpg);` / `&head(hero.jpg,180);`

## 画像の解決

`attach/`（`UPLOAD_DIR`）内の `encode(ページ名)_encode(ファイル名)` を参照し、存在・画像形式を検証したうえで `?plugin=attach&refer=...&openfile=...` の URL を出力します（`#img` と同系）。

対応拡張子: `.jpg` / `.jpeg` / `.png` / `.gif` / `.webp`
