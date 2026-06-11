# PRODUCT-STRATEGY — PukiWiki2026 Plus プロダクト方針

**ステータス:** 確定（2026-06-12）  
**対象読者:** プロジェクトオーナー・開発者  
**関連:** [README.md](../README.md) · [UPGRADE.md](UPGRADE.md) · [CHANGELOG.md](../CHANGELOG.md) · [plus/README.md](../plus/README.md)

---

## 0. 確定アーキテクチャ（必読）

| # | 原則 | 内容 |
|---|------|------|
| 1 | **Fork としての位置づけ** | PukiWiki2026 Plus は [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の **Fork 版**（拡張エディション） |
| 2 | **適用方式** | 稼働中の PukiWiki2026 インストールへ overlay を **上書きコピー** すると Plus 相当になる |
| 3 | **リポジトリの責務** | **Plus リポジトリ内で PukiWiki2026 を改変しない** — 全ツリー同梱の改造 fork ではない |

**実装の要点:** プロダクト上は Fork だが、リポジトリは **差分（overlay）・適用スクリプト・Plus 向けドキュメントのみ** を管理する。Core の PHP/JS/CSS 改修は [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) で行う。

---

## 1. 概要

PukiWiki2026 Plus は、稼働中の PukiWiki2026 に **overlay（差分ファイル）を上書き適用** して Plus 相当の環境にする **拡張パック** です。

| 原則 | 内容 |
|------|------|
| **Core は PukiWiki2026** | セキュリティ強化・LTS 保守は PukiWiki2026 リポジトリのみ |
| **Plus は overlay のみ** | 本リポジトリには Plus 固有ファイル・`upgrade/` スクリプト・`docs/` のみを置く |
| **適用** | [UPGRADE.md](UPGRADE.md) の手順どおり既存インストールへ上書き |

---

## 2. 2 リポジトリ構成

```
PukiWiki2026/                    ← Core（セキュリティ強化 LTS）
├── index.php
├── pukiwiki/
│   ├── lib/
│   ├── plugin/
│   ├── skin/
│   └── docs/
└── CHANGELOG.md

PukiWiki2026-Plus/               ← Plus（overlay パック）★ 本リポジトリ
├── docs/
│   ├── PRODUCT-STRATEGY.md      ← 本ファイル
│   └── UPGRADE.md
├── plus/                        ← overlay ファイル（目標の正本）
│   └── pukiwiki-plus/           ← 推奨: Core と物理分離
├── upgrade/                     ← 適用スクリプト（将来 scripts/upgrade へ移行可）
│   ├── apply.ps1
│   └── apply.sh
├── CHANGELOG.md
└── pukiwiki/                    ← LEGACY（後述 §2.2）
```

### 2.1 なぜ 2 リポジトリ + overlay か

| 方式 | 問題 |
|------|------|
| 単一リポジトリ + 全ツリー fork 同梱 | Core 改変が Plus リポに混入。upstream 追従コストが 2 倍 |
| 単一リポジトリ + ブランチ分離 | セキュリティ fix の双方向 cherry-pick が常態化 |
| **2 リポジトリ + overlay** | 責務が明確。Plus は delta のみ管理 |

### 2.2 LEGACY `pukiwiki/` と移行計画

**現状（2026-06-12）:** 初期コミット（`8e942d3`）では PukiWiki2026 の全 `pukiwiki/` ツリーを同梱した **旧 fork 構成** が残っている。これは確定方針（§0）に反するため **LEGACY** として扱う。

| フェーズ | 作業 | 状態 |
|----------|------|------|
| **A. ドキュメント整備** | overlay モデルを README / 本ファイル / UPGRADE に明文化 | ✅ 本セッション |
| **B. overlay 受け皿** | `plus/`・`upgrade/` を追加。新規 Plus 機能はここにのみ追加 | ✅ 骨格完了 |
| **C. 機能の切り出し** | dragdrop / ref popup / oEmbed 等を `plus/pukiwiki-plus/` へ移行 | 未着手 |
| **D. Core ローダー** | PukiWiki2026 側で edition=`plus` 時に `pukiwiki-plus/` を読む改修 | 未着手（Core リポ） |
| **E. LEGACY 削除** | `pukiwiki/` ツリー全体をリポジトリから除去 | **未実施**（オーナー確認後） |

**開発ルール（移行中）:**

- 新規コミットで LEGACY `pukiwiki/` 内のファイルを **改変しない**
- Plus 固有の追加・変更は **`plus/` のみ**
- LEGACY 参照用ドキュメント（`pukiwiki/docs/*`）は `docs/` へ移すか、リンクを root `docs/` に差し替える

---

## 3. 適用フロー

詳細手順は [UPGRADE.md](UPGRADE.md)。概要:

### 3.1 新規導入（Core → Plus）

```
1. PukiWiki2026 を設置・稼働確認
2. PukiWiki2026 Plus を clone / ダウンロード
3. バックアップ取得
4. upgrade/apply.ps1 または apply.sh を実行
   → plus/ 配下がインストールルートへミラー配置される
5. 動作確認（将来: ini で edition=plus を有効化）
```

### 3.2 Plus 版アップデート

```
1. PukiWiki2026 Core を最新化（上流 DEPLOY.md §4.7）
2. Plus overlay を最新版で再適用
3. wiki/・attach/ は触らない
```

### 3.3 ダウングレード（Plus → Core のみ）

```
1. overlay で追加した pukiwiki-plus/ を削除
2. plus/pukiwiki/ 経由で上書きした Core ファイルを復元
3. Plus 専用記法ページを人手確認
```

---

## 4. overlay 配置規則

### 4.1 推奨: `plus/pukiwiki-plus/`

```
plus/
└── pukiwiki-plus/
    ├── lib/              … Plus 専用 lib（例: oembed 拡張）
    ├── plugin/           … Plus プラグイン
    ├── skin/             … edit-dragdrop.js, ref-popup.js 等
    └── README.md
```

Core の `pukiwiki/` を直接上書きしない。PukiWiki2026 側ローダー（将来）が edition=plus 時に探索パスへ追加する。

### 4.2 上書き方式（最小限）

```
plus/
└── pukiwiki/
    └── skin/
        └── pukiwiki.css    ← 差し替えが unavoidable な場合のみ
```

`upgrade/` スクリプトが `{インストールルート}/` へミラー配置する。

**判断基準:**

> 「Plus 未適用時に PukiWiki2026 Core だけが残るか？」  
> → Yes なら Plus overlay / No なら PukiWiki2026（Core）で改修

---

## 5. Core / Plus の機能境界

### 5.1 Core（PukiWiki2026）

| 分類 | 例 |
|------|-----|
| セキュリティ | 編集認証、CSRF、Unicode 検証、captcha、spamfilter |
| 認証 UX | changepassword、auth_ini |
| 運用 | perm.php、起動時パーミッション |
| 互換 | upstream 1.5.4 標準 plugin 一式 |

### 5.2 Plus（overlay）

| 分類 | 配置先（案） | 備考 |
|------|-------------|------|
| 編集 D&D / クリップボード貼り付け | `pukiwiki-plus/skin/edit-dragdrop.js` | LEGACY Core から移行予定 |
| #ref ポップアップ | `pukiwiki-plus/skin/ref-popup.js` 等 | 同上 |
| oEmbed 拡張 | `pukiwiki-plus/lib/oembed.php` | Core から分離予定 |
| 編集レイアウト CSS | `pukiwiki-plus/skin/edit-layout.css` | 同上 |
| 将来: Markdown、API、新スキン | `pukiwiki-plus/` 初出 | — |

---

## 6. バージョニング

| エディション | semver | リポジトリ |
|-------------|--------|-----------|
| **Core** | **1.x.y** | [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) |
| **Plus** | **2.x.y** | **PukiWiki2026-Plus**（本リポ） |

- ページデータ（`wiki/`）は Core → Plus へそのまま移行可能
- Plus → Core ダウングレードは Plus 専用記法依存ページで非保証
- Core のセキュリティ fix は PukiWiki2026 で先行 → Plus overlay 再適用で追従

---

## 7. 開発ワークフロー

1. 機能が Core か Plus か判定（§5）
2. Core なら → PukiWiki2026 で PR
3. Plus なら → `plus/` に overlay 追加
4. `upgrade/` でローカル Core インストールへ適用して検証（`_local/` は `.gitignore`）
5. [CHANGELOG.md](../CHANGELOG.md) に記録

**PR ルール:** Plus リポの PR は `plus/`・`upgrade/`・`docs/` の変更に限定。LEGACY `pukiwiki/` の改変は受け付けない。

---

## 8. ロードマップ

| # | タスク | 状態 |
|---|--------|------|
| 1 | overlay 型ドキュメント整備 | ✅ |
| 2 | `plus/`・`upgrade/` 骨格 | ✅ |
| 3 | LEGACY 機能を `plus/` へ移行 | 未着手 |
| 4 | PukiWiki2026 側 edition ローダー | 未着手（Core） |
| 5 | LEGACY `pukiwiki/` 削除 | 未着手（要確認） |
| 6 | Plus v2.0.0 初回リリース | 未着手 |

---

## 9. 決定ログ

| 日付 | 決定 |
|------|------|
| 2026-06-10 | 版分け方針ドキュメント初版（単一リポジトリ案） |
| 2026-06-12 | **2 リポジトリ + overlay を採用** — Plus リポに Core 改変を含めない |
| 2026-06-12 | LEGACY `pukiwiki/` は移行完了まで残す（一括削除はしない） |
| 2026-06-12 | semver: 1.x = Core、2.x = Plus |

---

*Core 側の詳細設計は [PukiWiki2026 pukiwiki/docs](https://github.com/kuwa2005/PukiWiki2026/tree/main/pukiwiki/docs) を参照。*
