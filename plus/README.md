# plus/ — Plus overlay ファイル

このディレクトリには、PukiWiki2026 上に上書き適用する **Plus 固有のファイルのみ** を置きます。

## 配置ルール

overlay のディレクトリ構造は、適用先の PukiWiki2026 インストールに対する **相対パス** です。

```
plus/
├── pukiwiki-plus/          ← 推奨: Plus 専用ディレクトリ（Core と物理分離）
│   ├── lib/
│   ├── plugin/
│   └── skin/
└── pukiwiki/               ← 既存ファイルの上書きが必要な場合のみ
    └── skin/
        └── edit-layout.css
```

### 推奨: `pukiwiki-plus/` 方式

Plus 専用ファイルは `pukiwiki-plus/` 配下に置き、PukiWiki2026 Core の `pukiwiki/` を直接上書きしない方針を推奨します。ローダー改修（`lib/plugin.php` 等）は PukiWiki2026 側で行い、edition=plus 時に `pukiwiki-plus/` を読み込む設計です。

### 上書き方式

Core の既存ファイルを差し替える必要がある場合は、`plus/pukiwiki/` 配下にミラー配置します。`upgrade/` スクリプトが対象インストールへコピーします。

## 現状

Plus 固有の overlay ファイルは **未実装** です。以下は将来追加予定の候補です（[docs/PRODUCT-STRATEGY.md](../docs/PRODUCT-STRATEGY.md) 参照）:

| 機能 | 配置先（案） |
|------|-------------|
| 編集 D&D / クリップボード貼り付け | `pukiwiki-plus/skin/edit-dragdrop.js` |
| #ref ポップアップ | `pukiwiki-plus/skin/ref-popup.js` |
| oEmbed 拡張 | `pukiwiki-plus/lib/oembed.php`, `pukiwiki-plus/plugin/oembed.inc.php` |
| 編集レイアウト CSS | `pukiwiki-plus/skin/edit-layout.css` |

## 注意

- **PukiWiki2026 Core のファイルをここにコピーしないでください。** Core の変更は [PukiWiki2026 リポジトリ](https://github.com/kuwa2005/PukiWiki2026) で行います。
- 新規 overlay 追加時は [CHANGELOG.md](../CHANGELOG.md) に記録してください。
