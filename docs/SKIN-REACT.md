# SKIN-REACT — Plus 既定スキン（React）

`pukiwiki/skin/` は Plus の既定 UI スキンです。Vite + React 18 のアプリシェルが PukiWiki のサーバー描画 HTML（`$body` / `$menu` 等）を DOM 移譲で再配置し、Notion / Linear 風の contemporary web app 体験を提供します。

**方針（2026-06-12）:** 旧 `skin2026/` は `skin/` に統合済み。`SKIN_DIR` の既定は `pukiwiki/skin/`。wiki データ（`.txt`・添付）互換を最優先し、必要なら `lib/`・`plugin/` も Plus 内で改変可（Core ローカル `D:\00_project\pukiwiki2026` は触らない）。

## 有効化

`pukiwiki/pukiwiki.ini.php`（git 除外）の既定:

```php
define('SKIN_DIR', 'pukiwiki/skin/');
```

雛形: `pukiwiki/pukiwiki.ini.php.example`

代替スキン（`tdiary.skin.php`・`keitai.skin.php`）は `SKIN_FILE` で切り替え可能。

## アーキテクチャ

```
pukiwiki.skin.php (PHP)
  ├─ 非表示 #skin-app-ssr に PukiWiki 出力（#body, #menubar, h1.title …）
  ├─ #skin-app-config JSON（ナビ・リンク・ページ状態）
  └─ dist/skin-app.js（React IIFE, flushSync 同期マウント）

React App (src/)
  ├─ サイドバー（折りたたみ + menu プラグイン）
  ├─ トップバー + ヒーロー（タイトル / topicpath）
  ├─ コマンドパレット（⌘K / Ctrl+K）
  ├─ モバイルボトムナビ + 編集 FAB
  └─ useLayoutEffect で SSR ノードをスロットへ移譲（ID 維持）

PukiWiki JS（skin/ 内）
  main.js / search2.js / ref-popup.js / edit-dragdrop.js
```

### PukiWiki 互換の要点

| 要素 | 用途 |
|------|------|
| `#body` | 本文・編集フォーム。main.js / プラグイン |
| `h1.title` | topicpath 書き換え（main.js） |
| `#attach` / `#toolbar` | edit-dragdrop.js |
| `#pukiwiki-site-properties` | `$html_scripting_data`（Core 出力） |

React は **HTML を再生成せず** 既存ノードを移動するため、フォーム・プラグイン出力・#ref 画像はそのまま動作します。

## ビルド

```bash
cd pukiwiki/skin
npm install
npm run build   # → dist/skin-app.js, dist/skin-app.css
```

`dist/` をコミットに含めます（PHP サーバーから CDN ビルド不要で配信）。

開発時:

```bash
npm run dev     # Vite dev server（PHP 連携は手動）
```

## デザイン

- グラスモーフィズム + グラデーションオーブ背景
- ダーク / ライト（`localStorage: pukiwiki-skin-theme`）
- レスポンシブ: 1024px 未満はドロワー + ボトムナビ
- Wiki 本文 typography: `pukiwiki.css`（プラグイン・編集 UI）

## デプロイ

上書きデプロイで `skin/`（**`dist/` 含む**）を本番へコピー。[UPGRADE.md](UPGRADE.md)

## 制限・既知事項

- **ビルド必須:** `npm run build` 後の `dist/` が無いと React シェルは表示されない（本文 SSR は `#skin-app-ssr` に残るが非表示）。
- **toolbar:** 旧来の画像ツールバーは DOM 互換用に `#toolbar` を hidden 保持。UI は React ナビ / FAB / コマンドパレットに集約。
- **topicpath:** `SKIN_DEFAULT_DISABLE_TOPICPATH` 既定 0（topicpath 表示）。Core main.js が `h1.title` を書き換える挙動は維持。

## 開発と本体改変

変更は Plus リポジトリの `pukiwiki/skin/` を第一選択とする。`lib/`・`plugin/` の改変は **wiki データ互換を保つ範囲** で Plus 内で実施可。Core (`D:\00_project\pukiwiki2026`) には触れない。

本体依存が Core 側の修正を要する場合は [CORE-BOUNDARY.md](CORE-BOUNDARY.md) §6 に従い handoff を残す。

### 要確認（暫定方針）

| 項目 | 現状・懸念 | 対応 |
|------|------------|------|
| **`edit-dragdrop.js` と `#toolbar`** | React シェルは `#toolbar` を非表示化。編集 D&D は `#toolbar` を DOM 移譲で ID 維持 | skin 内で対応 |
| **`$head_tag` / プラグイン注入** | flushSync 同期マウント＋SSR ノード移譲で大半は動作想定 | 個別プラグイン要確認 |
| **コマンドパレット検索** | ナビフィルタ＋検索ページリンク（全文 API は未実装） | 将来 handoff 可 |

### 実装状況

| 機能 | 状態 |
|------|------|
| アプリシェル（サイドバー / トップバー / ヒーロー） | 実装済 |
| コマンドパレット（⌘K / Ctrl+K） | 実装済 |
| ダーク / ライト | 実装済（`pukiwiki-skin-theme`） |
| `$body` / `$menu` DOM 移譲 | 実装済（`adoptNode`、ID 維持） |
| `ref-popup.js` | defer 読込（`#body` 内 #ref 向け） |
| `edit-dragdrop.js` | 編集時のみ defer 読込 |
| モバイル（ドロワー + ボトムナビ + FAB） | 実装済 |

## 移行（skin2026 から）

旧 `pukiwiki/skin2026/` は廃止。`pukiwiki.ini.php` で `skin2026` を指定している場合は `pukiwiki/skin/` に戻す。テーマ localStorage キーは `pukiwiki-skin-theme`（旧 `pukiwiki-skin2026-theme` とは別）。
