# PRODUCT-STRATEGY — PukiWiki2026 版分け方針

**ステータス:** 提案（2026-06-10）  
**対象読者:** プロジェクトオーナー・開発者  
**関連:** [ARCHITECTURE.md](./ARCHITECTURE.md) · [GIT-WORKFLOW.md](./GIT-WORKFLOW.md) · [UPSTREAM.md](./UPSTREAM.md) · [CHANGELOG.md](../../CHANGELOG.md)

---

## 1. 背景と課題

| フェーズ | 方針 | 成果 |
|----------|------|------|
| **これまで（v1.0.x）** | セキュリティ強化 + 僅かな UX 改善 | 認証必須・CSRF・監査対応・編集 D&D 等 |
| **これから** | 「2026 版」と言える積極的機能追加 | 未着手 |
| **制約** | **シンプルかつ軽量な PukiWiki も残す** | 版の分離方法が未決 |

本ドキュメントは実装ではなく、**Simple（Core）版と Plus（拡張）版をどう共存させるか**の戦略提案である。

---

## 2. 現状調査サマリ

### 2.1 リポジトリ構成

```
pukiwiki2026/                 ← git root（単一リポジトリ）
├── index.php                 ← エントリ（DATA_HOME = ./pukiwiki/）
├── README.md, CHANGELOG.md
├── .github/workflows/php.yml
└── pukiwiki/                 ← デプロイ / バックアップ単位
    ├── lib/, plugin/, skin/
    ├── docs/, tools/
    └── pukiwiki.ini.php.example
```

- **ブランチ:** `main` が唯一の安定幹。`feature/*` → PR → `main`（[GIT-WORKFLOW.md](./GIT-WORKFLOW.md)）
- **リリース:** `v1.0.0` / `v1.0.1` タグ済み。main には [Unreleased] 改善あり
- **過去の分離試行:** PR #37（`skin/classic` / `skin/forge`）・PR #39（React forge）→ **PR #41 で巻き戻し**（[CHANGELOG.md](../../CHANGELOG.md) v1.0.1）。`docs/DESIGN.md` も削除済み

### 2.2 upstream 1.5.4 との差分規模

比較基準: git タグ `upstream-1.5.4-utf8`（[UPSTREAM.md](./UPSTREAM.md)）

| 範囲 | 規模（概算） | 備考 |
|------|-------------|------|
| リポジトリ全体 | 264 files, **+7,774 / −205** | docs・ディレクトリ集約を含む |
| **`lib/`（実質改造）** | 既存 8 ファイル改修 + **新規 9 ファイル** | 改修合計 ≈ +565 / −39 行 |
| **`plugin/`** | 既存 90 件超 + 新規 2 件（`changepassword`, `oembed`） | `attach` +150/−35、`ref` +46/−3 等 |
| **`skin/`** | JS 新規 2 件 + CSS 拡張 | `edit-dragdrop.js`（353 行）、`ref-popup.js`（65 行） |
| **`index.php`** | +9 / −7 | `DATA_HOME` 集約 |

**新規 `lib/` ファイル（2026 固有）:**

`akismet.php`, `auth_ini.php`, `captcha.php`, `comment.php`, `csrf.php`, `oembed.php`, `perm.php`, `security.php`, `spamfilter.php`

**所見:** コア改変はセキュリティ・認証が中心で、UX 拡張（D&D・ref popup）は **skin + 既存 plugin の拡張**に留まっている。現時点でも「分離可能な塊」は存在する。

### 2.3 PukiWiki コミュニティの慣習

| 慣習 | 内容 | PukiWiki2026 への示唆 |
|------|------|----------------------|
| **プラグイン拡張** | 標準 70+ プラグイン + 個人自作が `plugin/` に配置 | 新機能は plugin 優先が自然 |
| **公式の版分けなし** | 1.5.x 系は単一ライン。セキュリティ fix は dev サイトで継続 | 「Core / Plus」は **fork 側の独自ブランド**として設計 |
| **派生は個別公開** | Markdown 混在・下書き機能等は個人 Wiki / GitHub で公開 | 2 リポジトリ分割より **同梱 optional** の方が導入ハードルが低い |
| **プラグイン置き場パターン** | 例: [kanateko/pukiwiki-plugin](https://github.com/kanateko/pukiwiki-plugin) — plugin/skin をコピー導入 | **Plus Pack** ディレクトリ方式と親和性が高い |

### 2.4 参考: 他プロダクトの分離（参考程度）

| 例 | 方式 | PukiWiki への当てはめ |
|----|------|----------------------|
| WordPress Classic vs Blocks | 同一コア + エディタ / テーマで体験分岐 | PHP 単体・プラグイン文化の PukiWiki には **そのまま移植しにくい** |
| WordPress Plugin | コア薄 + 機能は plugin | **最も近い** — PukiWiki 本来の拡張モデル |
| LTS / Current（Node 等） | ブランチ別保守 | 小規模 fork では **merge コスト > メリット** になりやすい |

---

## 3. 分離パターン比較

### A. 単一リポジトリ + 2 ブランチ（`main` vs `develop` / `plus`）

| | |
|---|---|
| **概要** | Simple を `main`、拡張を `plus` ブランチで並行開発 |
| **メリット** | ブランチ名で意図が明確 |
| **デメリット** | セキュリティ fix の **双方向 cherry-pick** が常態化。v1.0.1 で classic/forge 分岐を巻き戻した教訓と同型の **merge 地獄** |
| **判定** | ❌ 非推奨（小規模チーム・単一メンテナ向け） |

### B. 単一リポジトリ + 2 リリースライン（タグで区別）

| | |
|---|---|
| **概要** | コードは 1 幹。`v1.x.y` = Core、`v2.x.y` = Plus として **リリース内容**を切り分け |
| **メリット** | 開発は 1 本化。ユーザーは semver で選べる。LTS イメージを作りやすい |
| **デメリット** | 「同じ commit から 2 種類の zip を切る」運用設計が必要 |
| **判定** | ✅ **推奨（主軸）** |

### C. モノレポ内サブプロダクト（`pukiwiki/` + `pukiwiki-plus/`）

| | |
|---|---|
| **概要** | Core は `pukiwiki/`、拡張 plugin / skin / JS を `pukiwiki-plus/` に分離 |
| **メリット** | デプロイ単位が物理的に分かれる。Plus 未導入 = 軽量を保証 |
| **デメリット** | ローダー（plugin 探索・skin 読込）の改修が必要。境界設計を誤ると Core から Plus へ依存が漏れる |
| **判定** | ✅ **推奨（実装手段として B とセット）** |

### D. 2 リポジトリ（Simple + Forge）

| | |
|---|---|
| **概要** | `PukiWiki2026` と `PukiWiki2026-Forge` を完全分割 |
| **メリット** | ブランド・Issue・リリースサイクルを完全独立 |
| **デメリット** | セキュリティ patch の **二重適用**、GPL ソース同期、upstream 追従の **2 倍コスト** |
| **判定** | ❌ 非推奨（現規模・単一メンテナ前提） |

### E. ビルド時フラグ / エディション ini（`PKWK2026_EDITION`）

| | |
|---|---|
| **概要** | `pukiwiki.ini.php` の `$pukiwiki2026_edition = 'core'|'plus'` で機能 ON/OFF |
| **メリット** | 同一 zip から切替可能。A/B テスト・段階導入に便利 |
| **デメリット** | コードは両方入るため **ディスク上は軽量にならない**（未使用 JS 読込を止めれば HTTP は軽量化可） |
| **判定** | ✅ **推奨（B/C の運用補助）** — 単独では「物理的分離」にならない |

### F. プラグイン・オプション化（新機能はすべて optional plugin）

| | |
|---|---|
| **概要** | Core は upstream 近傍 + 必須セキュリティのみ。拡張は plugin / skin に集約 |
| **メリット** | PukiWiki 文化と一致。upstream 追従コスト最小 |
| **デメリット** | 既存の **コア組込み**（認証ゲート、CSRF 注入等）は plugin 化しにくい。UX 系も skin フック設計が要る |
| **判定** | ✅ **推奨（拡張機能の原則）** — セキュリティ基盤は Core 例外 |

---

## 4. 推奨案

### 4.1 第一推奨: **B + C + F（＋ E を ini で補助）**

**名称:**

| エディション | semver | ブランド名（案） | 位置づけ |
|-------------|--------|-----------------|----------|
| **Core** | **1.x.y** | PukiWiki2026 Core | セキュリティ強化 LTS。upstream 近傍 + 必須安全機能 |
| **Plus** | **2.x.y** | PukiWiki2026 Plus | 2026 拡張。UX・メディア・外部連携・実験機能 |

**原則:**

1. **git は 1 幹（`main`）** — エディション別ブランチは作らない
2. **物理配置で軽量を担保** — Plus 専用は `pukiwiki-plus/`（または `pukiwiki/plugin-plus/` + `pukiwiki/skin-plus/`）
3. **新機能は原則 plugin / skin 追加** — Core の `lib/` 改変はセキュリティ・互換性理由がある場合のみ
4. **ini で実行時エディション宣言** — `$pukiwiki2026_edition` と `$pukiwiki2026_plus_enabled`（後述）

### 4.2 第二推奨（将来オプション）: **F 徹底 + 単一 semver**

Plus Pack を別ディレクトリにせず、すべて `plugin/` + ini 既定 OFF で運用する **ミニマル案**。  
チームが 1 人・リリース zip を分けない場合の簡易版。ただし **「Plus 未導入 = ファイルが存在しない」** という明確さは C 方式に劣る。

---

## 5. 具体設計

### 5.1 ブランチ戦略

| ブランチ | 用途 | 変更 |
|----------|------|------|
| `main` | 唯一の開発幹 | **現行どおり** |
| `feature/*` | 機能開発 | ラベル `edition:plus` または `edition:core` を PR で付与（GitHub 反映は任意） |
| `fix/*` | バグ・セキュリティ | 原則 **両エディションに含める** |

**やらないこと:** `core` / `plus` 長寿命ブランチの維持（パターン A を避ける）

### 5.2 バージョニング

```
1.x.y — Core Edition（LTS セキュリティトラック）
  ↑ 1.x.(y+1) = セキュリティ・互換 fix のみ（原則）
  ↑ 1.(x+1).0 = Core でも許容する小さな改善（要 CHANGELOG 明記）

2.x.y — Plus Edition（機能トラック）
  ↑ 2.0.0 = Plus Pack 初回 bundling
  ↑ 2.x.(y+1) = Plus 機能追加・UX 改善
  ↑ セキュリティ fix は lib/ 共通部分を Core と **同時に** patch（2.x.(y+1) と 1.x.(y+1) を近接リリース）
```

**互換ルール:**

- **ページデータ（`wiki/`）は 1.x → 2.x へそのまま移行可能**（マイグレーション不要を目標）
- **2.x → 1.x ダウングレード**は Plus 専用記法・添付 API に依存したページがある場合 **非保証**
- Core から Plus へは **`pukiwiki-plus/` を追加 + ini 変更**のみ（[DEPLOY.md §4.7](./DEPLOY.md#47-既存環境のアップデート稼働中-wiki) の「プログラム部分だけコピー」を拡張）

### 5.3 Core / Plus の機能境界（案）

#### Core に残す（必須・LTS 対象）

| 分類 | 機能 | 理由 |
|------|------|------|
| セキュリティ | 編集認証、`csrf.php`, `security.php`, Unicode 検証 | 2026 の存在意義。plugin 化不可 |
| セキュリティ | `captcha.php`, `spamfilter.php`, `akismet.php` | ini 既定 OFF でも **コードは Core**（有効化は任意） |
| 認証 UX | `changepassword`, `auth_ini.php`, ログイン済み `$adminpass` 省略 | セキュリティ運用の一部 |
| 運用 | `perm.php`, 起動時パーミッション | 本番安定性 |
| 互換 | upstream 1.5.4 標準 plugin 一式 | 公式互換のベースライン |

#### Plus に移す / 新規追加する（拡張）

| 分類 | 現状の所在 | Plus 側の置き場（案） |
|------|-----------|----------------------|
| 編集 UX | `skin/edit-dragdrop.js`, `attach` API 拡張 | `skin-plus/edit-dragdrop.js`, `plugin-plus/attach_dragdrop.inc.php` |
| #ref 拡張 | `ref.inc.php` 改修, `ref-popup.js`, CSS | `plugin-plus/ref_enhanced.inc.php` または ref を fork して Plus のみ上書き |
| oEmbed | `lib/oembed.php`, `plugin/oembed.inc.php` | **`pukiwiki-plus/lib/oembed.php`** + plugin（Core からは削除） |
| 編集 textarea 全画面 | `pukiwiki.css` / `tdiary.css` | `skin-plus/edit-layout.css` |
| 凍結ページ comment | `lib/comment.php` 連携 | Core に **スパム防御として残し**、UI 改善のみ Plus |
| 将来: Markdown 混在、API、新スキン | — | すべて Plus 初出 |

**判断基準（迷ったとき）:**

> 「無効化 / 未インストール時に、upstream 1.5.4 + セキュリティ強化だけが残るか？」  
> → Yes なら Plus 候補 / No なら Core

### 5.4 ディレクトリ案（パターン C）

```
pukiwiki/
├── lib/              … Core（upstream 差分は最小）
├── plugin/           … 標準 + Core プラグイン
├── skin/             … 標準 3 スキン（upstream 近傍）
pukiwiki-plus/        … ★ Plus Pack（任意同梱）
├── lib/              … oembed 等 Plus 専用 lib
├── plugin/           … Plus プラグイン
├── skin/             … edit-dragdrop.js, ref-popup.js, 追加 CSS
└── README.md         … 依存: PukiWiki2026 Core 1.x+
```

**ローダー改修（将来実装）:**

- `lib/plugin.php` — `$plugin_dirs = array('plugin/', 'plugin-plus/')` を edition が plus のときのみ
- `skin/*.skin.php` — `$pukiwiki2026_edition === 'plus'` のとき `skin-plus/` の JS/CSS を追加読込

### 5.5 ini 設定（パターン E）

`pukiwiki.ini.php.example` に追加する想定:

```php
// PukiWiki2026 edition
// 'core' = Plus Pack 未使用（既定）
// 'plus' = pukiwiki-plus/ を読み込む
$pukiwiki2026_edition = 'core';

// Plus 個別機能の上書き（edition=plus 時のみ意味を持つ）
$pukiwiki2026_plus_features = array(
    'edit_dragdrop' => TRUE,
    'ref_popup'     => TRUE,
    'oembed'        => TRUE,
);
```

### 5.6 ドキュメント・Issue 運用

| 項目 | Core | Plus |
|------|------|------|
| CHANGELOG | `[1.x.y]` セクション | `[2.x.y]` セクション（同一ファイル内） |
| README | 「Core = 軽量・LTS」を明記 | Plus は optional とリンク |
| Issue ラベル（案） | `edition:core`, `security`, `upstream` | `edition:plus`, `ux`, `feature` |
| ARCHITECTURE.md | Core ADR | Plus 機能は §4 に追記 + `pukiwiki-plus/README.md` |

### 5.7 バックアップ / アップデート互換

| 操作 | 手順 |
|------|------|
| **Core のみバックアップ** | 現行どおり `index.php` + `pukiwiki/`（`pukiwiki-plus/` なし） |
| **Plus 利用時** | 上記 + `pukiwiki-plus/` |
| **Core → Plus アップグレード** | `pukiwiki-plus/` を追加、`$pukiwiki2026_edition = 'plus'`、[DEPLOY §4.7](./DEPLOY.md#47-既存環境のアップデート稼働中-wiki) に従い program 部分を更新 |
| **Plus → Core ダウングレード** | `pukiwiki-plus/` 削除 + edition を core に。Plus 記法ページは要人手確認 |
| **1.x → 2.x** | データ dir（`wiki/`, `attach/`）は触らない。program 更新のみ |

---

## 6. 短期で始められる第一歩

優先度順。**実装は別タスク** — ここでは方針決定とドキュメント整備のみ。

| # | タスク | 工数感 | 成果 |
|---|--------|--------|------|
| 1 | **本ドキュメントを関係者で合意** | 小 | 境界・semver の確定 |
| 2 | **CHANGELOG に `[2.0.0]` プレースホルダ** と Plus 機能一覧を書く | 小 | 2.x のスコープ可視化 |
| 3 | **既存 Unreleased 機能を Core / Plus に分類**（§5.3 表を埋める） | 小 | dragdrop / ref popup / oembed の行き先決定 |
| 4 | **`pukiwiki-plus/` 空ディレクトリ + README**（中身は次フェーズ） | 小 | 物理分離の受け皿 |
| 5 | **次 PR から `edition:core` / `edition:plus` ラベル運用**（ローカルでも可） | 小 | 開発時の迷い防止 |
| 6 | **v1.0.2（Core）を Unreleased 分まで patch リリース** — Plus 分離前の区切り | 中 | LTS 基点の確立 |
| 7 | **Plus 移行 PR（oembed → plus、dragdrop JS → skin-plus）** | 中〜大 | 2.0.0 の実体 |

**最初の 1 週間でやるなら:** 1 → 2 → 3 → 4（ドキュメントと分類のみ、コードは触らない）

---

## 7. リスクと対策

| リスク | 対策 |
|--------|------|
| Plus 機能が Core `lib/` に再び混入 | PR テンプレに「edition 区分」チェック項目。`git diff upstream-1.5.4-utf8:lib` の行数監視 |
| 2 系統のセキュリティ patch 漏れ | **同一 commit** を 1.x / 2.x 両タグに付ける運用。lib/ 共通変更は必ず両方 CHANGELOG |
| ユーザーが「どちらを入れるか」迷う | README に判断フロー: 「軽量・長期安定 → Core」「UX・メディア → Core + Plus」 |
| classic/forge 再発 | **スキンでエディション分けない**。Plus も default 3 skin を拡張する形に統一 |

---

## 8. 決定待ち事項（オーナー確認）

- [ ] ブランド名: **Core / Plus** でよいか（Forge / Standard 等の別名）
- [ ] semver: **1.x = Core, 2.x = Plus** でよいか
- [ ] 既存 Unreleased（dragdrop, ref popup）を **v1.0.2 Core に含めるか、2.0.0 まで待つか**
- [ ] `pukiwiki-plus/` を repo root 直下に置くか `pukiwiki/plugin-plus/` に留めるか

---

## 9. 関連 ADR（決定ログ追記用）

| 日付 | 決定 | 理由 |
|------|------|------|
| 2026-06-10 | 版分け方針ドキュメント作成 | Simple 維持 + 積極開発の両立検討 |
| （未決） | B+C+F 採用可否 | — |

---

*本ファイルはローカル提案。GitHub 反映はオーナー判断。*
