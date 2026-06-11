#!/usr/bin/env bash
# PukiWiki2026 Plus overlay を既存インストールへ適用する。
#
# Usage:
#   ./apply.sh /path/to/pukiwiki2026
#   ./apply.sh /path/to/pukiwiki2026 --dry-run
#
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PLUS_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)/plus"
TARGET="${1:-}"
DRY_RUN=false

if [[ "${2:-}" == "--dry-run" ]]; then
    DRY_RUN=true
fi

if [[ -z "$TARGET" ]]; then
    echo "Usage: $0 /path/to/pukiwiki2026 [--dry-run]" >&2
    exit 1
fi

# resolve target
TARGET="$(cd "$TARGET" && pwd)"

# --- validation ---
if [[ ! -f "$TARGET/index.php" ]]; then
    echo "Error: index.php not found in '$TARGET'. Specify PukiWiki2026 root." >&2
    exit 1
fi
if [[ ! -d "$TARGET/pukiwiki" ]]; then
    echo "Error: pukiwiki/ not found in '$TARGET'. Is this a PukiWiki2026 installation?" >&2
    exit 1
fi
if [[ ! -d "$PLUS_ROOT" ]]; then
    echo "Error: plus/ directory not found at '$PLUS_ROOT'." >&2
    exit 1
fi

# --- collect overlay files ---
mapfile -t FILES < <(find "$PLUS_ROOT" -type f ! -path "$PLUS_ROOT/README.md")

if [[ ${#FILES[@]} -eq 0 ]]; then
    echo "No overlay files to apply (plus/ contains only README)."
    echo "Plus overlay is not yet implemented. Nothing to copy."
    exit 0
fi

echo "PukiWiki2026 Plus overlay apply"
echo "  Source : $PLUS_ROOT"
echo "  Target : $TARGET"
if $DRY_RUN; then echo "  Mode   : DRY-RUN"; fi
echo ""

COPIED=0
for src in "${FILES[@]}"; do
    relative="${src#$PLUS_ROOT/}"
    dest="$TARGET/$relative"
    dest_dir="$(dirname "$dest")"

    if $DRY_RUN; then
        echo "  [dry-run] $relative -> $dest"
    else
        mkdir -p "$dest_dir"
        cp -f "$src" "$dest"
        echo "  Copied: $relative"
    fi
    COPIED=$((COPIED + 1))
done

echo ""
echo "Done. $COPIED file(s) copied."
