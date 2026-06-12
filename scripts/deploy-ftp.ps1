#Requires -Version 5.1
<#
.SYNOPSIS
  PukiWiki2026 Plus — FTP/FTPS 同期（WinSCP 推奨）

.DESCRIPTION
  リポジトリルートから本番へ、wiki データ・ini を除外して同期する。
  pukiwiki/pukiwiki.ini.php は常に転送しない（本番 READONLY 対策）。

.PARAMETER DryRun
  転送せず、同期対象ファイル一覧のみ表示する。
#>
param(
    [switch]$DryRun
)

$ErrorActionPreference = 'Stop'

$RepoRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path

function Import-DotEnv {
    param([string]$Path)
    if (-not (Test-Path -LiteralPath $Path)) {
        throw ".env が見つかりません: $Path`n.env.example をコピーして接続情報を設定してください。"
    }
    $vars = @{}
    Get-Content -LiteralPath $Path -Encoding UTF8 | ForEach-Object {
        $line = $_.Trim()
        if ($line -eq '' -or $line.StartsWith('#')) { return }
        $idx = $line.IndexOf('=')
        if ($idx -lt 1) { return }
        $key = $line.Substring(0, $idx).Trim()
        $val = $line.Substring($idx + 1).Trim()
        if (($val.StartsWith('"') -and $val.EndsWith('"')) -or ($val.StartsWith("'") -and $val.EndsWith("'"))) {
            $val = $val.Substring(1, $val.Length - 2)
        }
        $vars[$key] = $val
    }
    return $vars
}

function Get-EnvValue {
    param($Env, [string[]]$Keys, [string]$Default = '')
    foreach ($k in $Keys) {
        if ($Env.ContainsKey($k) -and $Env[$k]) { return $Env[$k] }
    }
    return $Default
}

function Find-WinScpCom {
    param([string]$Configured)
    if ($Configured -and (Test-Path -LiteralPath $Configured)) {
        return (Resolve-Path -LiteralPath $Configured).Path
    }
    $candidates = @(
        "${env:ProgramFiles}\WinSCP\WinSCP.com",
        "${env:ProgramFiles(x86)}\WinSCP\WinSCP.com"
    )
    foreach ($p in $candidates) {
        if (Test-Path -LiteralPath $p) { return $p }
    }
    return $null
}

function Test-DeployExclude {
    param([string]$RelativePath)
    $rel = $RelativePath -replace '\\', '/'
    $rel = $rel.TrimStart('./')

    if ($rel -eq 'pukiwiki/pukiwiki.ini.php') { return $true }
    if ($rel -match '^pukiwiki/wiki/') { return $true }
    if ($rel -match '^pukiwiki/attach/') { return $true }
    if ($rel -match '^pukiwiki/cache/') { return $true }
    if ($rel -match '^pukiwiki/backup/') { return $true }
    if ($rel -match '^pukiwiki/diff/') { return $true }
    if ($rel -match '^pukiwiki/counter/') { return $true }
    if ($rel -eq '.env') { return $true }
    if ($rel -match '^\.git') { return $true }
    if ($rel -match '/node_modules/') { return $true }
    if ($rel -match '^pukiwiki/skin/src/') { return $true }
    if ($rel -match '^pukiwiki/skin/node_modules/') { return $true }
    if ($rel -match '^\.cursor/') { return $true }

    return $false
}

function Get-DeployFiles {
    param([string]$Root)
    $files = New-Object System.Collections.Generic.List[string]
    Get-ChildItem -LiteralPath $Root -Recurse -File -Force | ForEach-Object {
        $rel = $_.FullName.Substring($Root.Length).TrimStart('\', '/')
        if (-not (Test-DeployExclude -RelativePath $rel)) {
            [void]$files.Add($rel)
        }
    }
  return $files
}

$envFile = Join-Path $RepoRoot '.env'
$envVars = Import-DotEnv -Path $envFile

$localDir = Get-EnvValue $envVars @('LOCAL_DIR')
if (-not $localDir) { $localDir = $RepoRoot }
$localDir = (Resolve-Path -LiteralPath $localDir).Path

$remoteDir = Get-EnvValue $envVars @('FTP_REMOTE_DIR')
if (-not $remoteDir) { throw 'FTP_REMOTE_DIR が .env に設定されていません。' }

$hostName = Get-EnvValue $envVars @('FTP_HOST')
$user = Get-EnvValue $envVars @('FTP_USER')
$password = Get-EnvValue $envVars @('FTP_PASSWORD')
$port = Get-EnvValue $envVars @('FTP_PORT' '21')
$protocol = (Get-EnvValue $envVars @('FTP_PROTOCOL' 'ftps')).ToLowerInvariant()
$excludeIni = Get-EnvValue $envVars @('FTP_EXCLUDE_INI' '1')

Write-Host "同期元: $localDir"
Write-Host "同期先: $remoteDir"
Write-Host "プロトコル: $protocol"
Write-Host "ini 除外: 常に有効 (FTP_EXCLUDE_INI=$excludeIni は明示用)"
Write-Host ''

$files = Get-DeployFiles -Root $localDir
Write-Host "対象ファイル数: $($files.Count)"

if ($DryRun) {
    Write-Host ''
    Write-Host '--- DryRun: 転送予定 ---'
    $files | Sort-Object | ForEach-Object { Write-Host $_ }
    Write-Host ''
    Write-Host 'DryRun 完了（転送なし）'
    exit 0
}

$winScp = Find-WinScpCom -Configured (Get-EnvValue $envVars @('FTP_WINSCP_PATH', 'WINSCP_PATH'))
if (-not $winScp) {
    throw 'WinSCP.com が見つかりません。WinSCP をインストールするか .env に FTP_WINSCP_PATH を設定してください。'
}

$remoteDirNorm = $remoteDir.Trim().TrimEnd('/')
switch ($protocol) {
    'ftp' { $url = "ftp://${hostName}:${port}/" }
    'ftps' { $url = "ftps://${hostName}:${port}/" }
    'sftp' { $url = "sftp://${hostName}:${port}/" }
    default { throw "未対応の FTP_PROTOCOL: $protocol (ftp | ftps | sftp)" }
}

$excludeRules = @(
    'pukiwiki/pukiwiki.ini.php',
    'pukiwiki/wiki/*',
    'pukiwiki/attach/*',
    'pukiwiki/cache/*',
    'pukiwiki/backup/*',
    'pukiwiki/diff/*',
    'pukiwiki/counter/*',
    '.env',
    '.git/*',
    '**/node_modules/*',
    'pukiwiki/skin/src/*',
    '.cursor/*'
)

$excludeArg = ($excludeRules | ForEach-Object { "|$($_)" }) -join ''

$scriptLines = @(
    "option batch abort",
    "option confirm off",
    "open $url -username=`"$user`" -password=`"$password`"",
    "cd `"$remoteDirNorm`"",
    "lcd `"$localDir`"",
    "synchronize remote . -mirror -filemask=`"$excludeArg`"",
    "exit"
)

$tempScript = [System.IO.Path]::GetTempFileName()
try {
    Set-Content -LiteralPath $tempScript -Value $scriptLines -Encoding UTF8
    Write-Host "WinSCP で同期中..."
    & $winScp "/script=$tempScript"
    if ($LASTEXITCODE -ne 0) {
        throw "WinSCP が終了コード $LASTEXITCODE で失敗しました。"
    }
    Write-Host 'FTP 同期が完了しました。'
}
finally {
    if (Test-Path -LiteralPath $tempScript) {
        Remove-Item -LiteralPath $tempScript -Force
    }
}
