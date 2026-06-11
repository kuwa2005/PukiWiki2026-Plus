#Requires -Version 5.1
<#
.SYNOPSIS
    PukiWiki2026 Plus overlay を既存インストールへ適用する。

.DESCRIPTION
    plus/ 配下のファイルを TargetPath へミラー配置でコピーする。
    wiki/・attach/ 等のデータディレクトリは変更しない。

.PARAMETER TargetPath
    PukiWiki2026 インストールのルートパス（index.php があるディレクトリ）。

.PARAMETER WhatIf
    コピーせず、対象ファイルを表示するだけ（ドライラン）。

.EXAMPLE
    .\apply.ps1 -TargetPath "C:\inetpub\pukiwiki2026"
    .\apply.ps1 -TargetPath "D:\00_project\pukiwiki2026" -WhatIf
#>
param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$TargetPath,

    [switch]$WhatIf
)

$ErrorActionPreference = 'Stop'

$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$PlusRoot  = Join-Path (Split-Path -Parent $ScriptDir) 'plus'
$Target    = (Resolve-Path -LiteralPath $TargetPath -ErrorAction SilentlyContinue)?.Path ?? $TargetPath

# --- validation ---
if (-not (Test-Path -LiteralPath (Join-Path $Target 'index.php'))) {
    Write-Error "index.php not found in '$Target'. Specify PukiWiki2026 root (where index.php lives)."
}
if (-not (Test-Path -LiteralPath (Join-Path $Target 'pukiwiki'))) {
    Write-Error "pukiwiki/ not found in '$Target'. Is this a PukiWiki2026 installation?"
}
if (-not (Test-Path -LiteralPath $PlusRoot)) {
    Write-Error "plus/ directory not found at '$PlusRoot'."
}

# --- collect overlay files (skip README-only placeholder check) ---
$files = Get-ChildItem -LiteralPath $PlusRoot -Recurse -File |
    Where-Object { $_.Name -ne 'README.md' -or $_.DirectoryName -ne $PlusRoot }

if ($files.Count -eq 0) {
    Write-Host "No overlay files to apply (plus/ contains only README)." -ForegroundColor Yellow
    Write-Host "Plus overlay is not yet implemented. Nothing to copy."
    exit 0
}

Write-Host "PukiWiki2026 Plus overlay apply"
Write-Host "  Source : $PlusRoot"
Write-Host "  Target : $Target"
if ($WhatIf) { Write-Host "  Mode   : DRY-RUN (WhatIf)" -ForegroundColor Cyan }
Write-Host ""

$copied = 0
foreach ($file in $files) {
    $relative = $file.FullName.Substring($PlusRoot.Length).TrimStart('\', '/')
    $dest     = Join-Path $Target $relative
    $destDir  = Split-Path -Parent $dest

    if ($WhatIf) {
        Write-Host "  [WhatIf] $relative -> $dest"
    } else {
        if (-not (Test-Path -LiteralPath $destDir)) {
            New-Item -ItemType Directory -Path $destDir -Force | Out-Null
        }
        Copy-Item -LiteralPath $file.FullName -Destination $dest -Force
        Write-Host "  Copied: $relative"
    }
    $copied++
}

Write-Host ""
Write-Host "Done. $copied file(s) $(if ($WhatIf) { 'would be ' })copied."
