# `#ref` プラグイン — 添付画像のインライン表示

添付ファイル（主に画像）をページ本文に埋め込む PukiWiki プラグインです。実装: `pukiwiki/plugin/ref.inc.php`。

## 基本記法

```
#ref(ファイル名)
#ref(ページ名/ファイル名)
#ref(ファイル名, オプション, ...)
```

ブロック記法 `{ref(...)}` も同様です。インライン記法 `&ref(...);` も利用できます。

| 条件 | 例 |
|------|-----|
| 現在ページの添付 | `#ref(photo.png)` |
| 別ページの添付 | `#ref(OtherPage/photo.png)` |
| 日本語ファイル名 | `#ref(スクリーンショット.png, middle)` |

ページ名とファイル名の区切りは **`ページ名/ファイル名`** を推奨します（`ref(ファイル, ページ)` 形式は非推奨・曖昧）。

## レイアウト・表示オプション

カンマ区切りで併用できます。

| オプション | 挙動 |
|-----------|------|
| `left` / `center` / `right` | 横方向の配置 |
| `wrap` / `nowrap` | 表で囲むか |
| `around` | テキスト回り込み（`float`） |
| `zoom` | `幅x高さ` 指定時に縦横比を保持して収める |
| `popup` | クリックでライトボックス表示（`ref-popup.js`） |
| `nolink` / `noimg` / `noicon` | リンク・画像・アイコンを抑制 |

## 画像サイズ指定

アスペクト比は常に維持されます（歪めません）。

### プリセット（コンテナ幅に対する割合）

| オプション | CSS 相当 |
|-----------|----------|
| `small` | `max-width: 30%` |
| `middle` | `max-width: 50%` |
| `big` | `max-width: 75%` |
| `full` | 元画像サイズ（`max-width: none`） |

### ピクセル固定（片辺 auto）

| 記法 | 意味 | 出力例 |
|------|------|--------|
| `640x` | 幅 640px・高さ auto | `style="width:640px;height:auto;max-width:100%"` |
| `x400` | 高さ 400px・幅 auto | `style="width:auto;height:400px;max-width:100%"` |
| `640w` | `640x` と同義 | 同上 |
| `400h` | `x400` と同義 | 同上 |

### ピクセル固定（幅・高さとも指定）

| 記法 | 意味 |
|------|------|
| `640x480` | 幅 640px・高さ 480px（`zoom` と併用で比率保持） |

### ビューポート幅（画面サイズ基準）

| 記法 | 意味 |
|------|------|
| `50%` | **画面（ビューポート）幅の 50%** — 画像の実サイズに対する拡大率ではない |
| `30%` | ビューポート幅の 30% |

`width: Nvw; height: auto; max-width: 100%` として出力します。モバイルで親要素よりはみ出さないよう `max-width: 100%` を付与します。

## 記法例

```
#ref(photo.png,middle)
#ref(photo.png,640x,popup,center)
#ref(photo.png,x400,right)
#ref(広告バナー.png,50%,center)
#ref(OtherPage/図解.png,30%,around)
#ref(photo.png,640x480,zoom)
```

## 関連

- 編集画面 D&D / 貼り付け: [EDIT-DRAGDROP.md](EDIT-DRAGDROP.md)
- ポップアップ UI: `pukiwiki/skin/ref-popup.js`
- スタイル: `pukiwiki/skin/pukiwiki.css`（`.ref-*`）、`pukiwiki/skin/tdiary.css`
