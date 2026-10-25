# ==============================================================================
# Script: run-local.ps1
# Purpose: Start local Laravel server on http://127.0.0.1:8000
# ==============================================================================

$baseDir = $PSScriptRoot
$env:PATH = "$baseDir\tools\php;$baseDir\tools\git\cmd;$env:PATH"

Write-Host ">>> Checking environment configuration..." -ForegroundColor Cyan
if (-not (Test-Path "$baseDir\.env")) {
    Copy-Item "$baseDir\.env.example" "$baseDir\.env"
}

if (-not (Test-Path "$baseDir\database\database.sqlite")) {
    New-Item -ItemType File -Path "$baseDir\database\database.sqlite" -Force | Out-Null
}

Write-Host ">>> Starting Laravel Web Server on http://127.0.0.1:8000" -ForegroundColor Green
Write-Host ">>> Test endpoint: http://127.0.0.1:8000/health" -ForegroundColor Yellow
Write-Host ">>> Press Ctrl+C to stop." -ForegroundColor Gray

& "$baseDir\tools\php\php.exe" -S 127.0.0.1:8000 -t "$baseDir\public"
