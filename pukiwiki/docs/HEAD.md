# HEAD — ヒーロー画像（`#head`）

ブログ風のページ先頭ヘッダー画像を、メインコンテンツ幅いっぱいに表示する Plus 向けプラグイン。

## 記法

```text
#head(hero.jpg)                  … 幅100%、画像の縦横比を維持（auto）
#head(hero.jpg,180)              … 高さ180pxで cover トリミング
#head(hero.jpg,small)            … プリセット高さ（下表）
#head(hero.jpg,1920x480)         … WxH 記法（高さ480pxで cover）
#head(hero.jpg,6432x3900)        … 原寸 WxH（高さ3900→上限2000pxで cover）
```

| 引数 | 説明 |
|------|------|
| 1 | 添付画像ファイル名（現在ページ、または `別ページ/ファイル名`） |
| 2 | 省略可。表示高さの指定（下記） |

### 第2引数（高さ）

| 形式 | 例 | 動作 |
|------|-----|------|
| 省略 | `#head(file)` | 幅100%・`height:auto`（巨大画像もコンテンツ幅に収まる） |
| 数値（px） | `#head(file,180)` | 1〜2000 の整数。`object-fit: cover` で中央トリミング |
| プリセット | `#head(file,small)` | 名前で高さを指定（大文字小文字無視・前後空白は trim） |
| WxH | `#head(file,1920x480)` | `幅x高さ` 形式。**高さ**を cover 高さに使う（`×` / `X` も可）。高さが2000超なら2000に丸める |

#### プリセット一覧

| 名前 | 高さ（px） |
|------|-----------|
| `small` | 120 |
| `medium` | 180 |
| `default` | 180 |
| `large` | 300 |

ブログ風ヘッダーには **`small` / `medium` / `large`** か、**`180`〜`300` 程度の数値**を推奨します。`6432x3900` のような原寸ピクセル数は上限2000pxに丸められるため、意図した見た目にならない場合は `#head(file,240)` のように表示高さを直接指定してください。

インライン: `&head(hero.jpg);` / `&head(hero.jpg,180);` / `&head(hero.jpg,small);`

## 画像の解決

`attach/`（`UPLOAD_DIR`）内の `encode(ページ名)_encode(ファイル名)` を参照します。解決手順は `#ref` と同じで、存在・画像形式を検証したうえで `?plugin=attach&refer=...&openfile=...` の URL を出力します（`#img` と同系）。

対応拡張子: `.jpg` / `.jpeg` / `.png` / `.gif` / `.webp`（末尾の拡張子で判定。`name_card2.png.png` のような二重拡張子も可）

- 現在ページの添付: `#head(ファイル名)`（`#ref(ファイル名)` と同じファイル名をそのまま指定）
- 別ページの添付: `#head(ページ名/ファイル名)`
- ファイル名に全角コロン `：` を含む場合も、添付名と一致すれば解決します（全角・半角の差異は自動で試行）
- 第2引数のカンマ区切りは PukiWiki 標準（`csv_explode`）。ファイル名にカンマが無ければ `#head(name_card2.png.png,small)` は正しく2引数に分割されます

例（KURAGASHI ページに添付がある場合）:

```text
#head(name_card2.png.png)
#head(name_card2.png.png,small)
#head(name_card2.png.png,180)
#head(name_card2.png.png,6432x3900)
#head(生成された画像：ネコ耳アイドルの音楽プロモーション.png)
#head(生成された画像：ネコ耳アイドルの音楽プロモーション.png,240)
```
