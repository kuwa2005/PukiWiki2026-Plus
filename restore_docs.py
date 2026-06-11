# -*- coding: utf-8 -*-
from pathlib import Path
import subprocess

def sh(cmd):
    subprocess.run(cmd, shell=True, check=True)

readme = Path("README.md")
readme.write_text("""# PukiWiki2026 Plus

**[PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) の Fork 版 — 直接開発ワークスペース**（非公式）

PukiWiki2026 Plus は PukiWiki2026 をベースにした **フルツリー同梱の開発用リポジトリ** です。`pukiwiki/` 配下を自由に編集し、Plus 固有の UX（`skin2026/` 等）をここで開発・検証します。

| 原則 | 内容 |
|------|------|
| **Plus = 直接開発** | 本リポジトリの `pukiwiki/` を自由に改変する |
| **Core = 参照のみ** | ローカル `D:\\00_project\\pukiwiki2026` は **読み取り専用参照**。Plus エージェントは触らない |
| **overlay 廃止** | 旧 `plus/`・`upgrade/` overlay モデルは **非推奨・削除済み** |

## Core 作業境界（エージェント・開発者向け）

**永続原則:** PukiWiki2026 Core のローカル作業ツリーは `D:\\00_project\\pukiwiki2026` ですが、**Plus 向け Cursor エージェントはそのパスへ一切書き込み・改変を行いません。**

| パス | 役割 | Plus エージェント |
|------|------|-------------------|
| `D:\\00_project\\pukiwiki2026` | Core 参照ツリー（読み取り専用） | **改変禁止** |
| `D:\\00_project\\pukiwiki2026 Plus` | Plus 直接開発ワークスペース | **ここだけで作業** |

- セキュリティ修正など Core 側が必要な変更は **handoff ドキュメント** で Core エージェント / [PukiWiki2026](https://github.com/kuwa2005/PukiWiki2026) リポジトリへ委譲する。
- 詳細: **[docs/CORE-BOUNDARY.md](docs/CORE-BOUNDARY.md)** · Cursor ルール: `.cursor/rules/pukiwiki2026-plus.mdc`

| 項目 | 内容 |
|------|------|
| 作業フォルダ | `D:\\00_project\\pukiwiki2026 Plus` |
| 設置・アップデート | [docs/UPGRADE.md](docs/UPGRADE.md) |
| 方針 | [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md) |

## クイックスタート

1. 本リポジトリを clone する
2. Web サーバー（PHP 8.x 推奨）に配置
3. `pukiwiki/pukiwiki.ini.php.example` を `pukiwiki/pukiwiki.ini.php` にコピーして設定

skin2026: [docs/SKIN2026.md](docs/SKIN2026.md)

## ドキュメント

- [docs/CORE-BOUNDARY.md](docs/CORE-BOUNDARY.md)
- [docs/SKIN2026.md](docs/SKIN2026.md)
- [docs/UPGRADE.md](docs/UPGRADE.md)
- [docs/PRODUCT-STRATEGY.md](docs/PRODUCT-STRATEGY.md)
""", encoding="utf-8")

Path("docs/PRODUCT-STRATEGY.md").write_text("""# PRODUCT-STRATEGY — PukiWiki2026 Plus

**ステータス:** 確定（2026-06-12）

Plus リポジトリ（`D:\\00_project\\pukiwiki2026 Plus`）は **pukiwiki/ 全ツリー同梱の直接開発ワークスペース** です。旧 overlay（`plus/`・`upgrade/`）は **廃止・削除** しました。

Core（`D:\\00_project\\pukiwiki2026`）は読み取り参照のみ。詳細は [CORE-BOUNDARY.md](CORE-BOUNDARY.md) · [UPGRADE.md](UPGRADE.md) · [SKIN2026.md](SKIN2026.md)。
""", encoding="utf-8")

Path("docs/UPGRADE.md").write_text("""# UPGRADE — PukiWiki2026 Plus

Plus は **フルツリー直接開発** リポジトリです（overlay 廃止）。

## 新規設置

1. リポジトリを clone
2. Web サーバー（PHP 8.x）に配置
3. `pukiwiki.ini.php.example` を `pukiwiki.ini.php` にコピー
4. 任意で [SKIN2026.md](SKIN2026.md) に従い skin2026 を有効化

## アップデート

```powershell
cd "D:\\00_project\\pukiwiki2026 Plus"
git pull origin main
```

本番反映時は `wiki/`・`attach/`・`pukiwiki.ini.php` を上書きしないこと。
""", encoding="utf-8")

Path("docs/SKIN2026.md").write_text("""# skin2026

```php
define('SKIN_DIR', 'pukiwiki/skin2026/');
define('SKIN_FILE', DATA_HOME . 'skin2026/pukiwiki.skin.php');
```

`pukiwiki/skin/` は変更しません。雛形は `pukiwiki.ini.php.example`。
""", encoding="utf-8")

cb = Path("docs/CORE-BOUNDARY.md").read_text(encoding="utf-8")
cb = cb.replace("Plus リポジトリ内のみ（`plus/`、`upgrade/`、`docs/` 等）", "Plus リポジトリ内の `pukiwiki/`・`docs/` 等を **自由に編集**")
cb = cb.replace("overlay、UX、メディア、実験機能、適用スクリプト", "UX、`skin2026`、メディア、実験機能、`pukiwiki/` 直接開発")
cb = cb.replace("Plus リポジトリに `pukiwiki/` 全ツリーを再同梱 | overlay 方針違反", "`pukiwiki/skin/` の直接改変 | 標準スキンは触らず `skin2026/` で拡張する")
if "## 7. overlay" in cb:
    cb = cb.split("## 7. overlay")[0].rstrip() + "\n"
Path("docs/CORE-BOUNDARY.md").write_text(cb, encoding="utf-8")

mdc = Path(".cursor/rules/pukiwiki2026-plus.mdc").read_text(encoding="utf-8")
mdc = mdc.replace("- `plus/` — overlay ファイル\n- `upgrade/` — 適用スクリプト（ユーザー明示の `-TargetPath` のみ。開発用 Core パス `D:\\00_project\\pukiwiki2026` を自律的に指定しない）\n", "- `pukiwiki/` — 直接開発（`skin/` は参照のみ、`skin2026/` で拡張）\n")
Path(".cursor/rules/pukiwiki2026-plus.mdc").write_text(mdc, encoding="utf-8")

ini = Path("pukiwiki/pukiwiki.ini.php.example")
lines = ini.read_text(encoding="utf-8").splitlines()
out = []
for line in lines:
    out.append(line)
    if line.strip() == "define('SKIN_DIR', 'pukiwiki/skin/');" and "skin2026" not in ini.read_text(encoding="utf-8"):
        out += [
            "// PukiWiki2026 Plus — skin2026:",
            "// define('SKIN_DIR', 'pukiwiki/skin2026/');",
            "// define('SKIN_FILE', DATA_HOME . 'skin2026/pukiwiki.skin.php');",
            "// 詳細: docs/SKIN2026.md",
        ]
ini.write_text("\n".join(out) + "\n", encoding="utf-8")

count = int(subprocess.check_output("git ls-files pukiwiki | find /c /v \"\"", shell=True).decode().strip().split()[-1])
print("count", count)
if count <= 200:
    raise SystemExit(f"too few pukiwiki files: {count}")
sh("git add -A")
sh('git commit --trailer "Co-authored-by: Cursor <cursoragent@cursor.com>" -m "fix: pukiwiki/ を復元し Plus 直接開発モデルに統一"')
sh("git push origin main")
print("COMMIT", subprocess.check_output("git rev-parse HEAD", shell=True).decode().strip())
