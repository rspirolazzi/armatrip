# Crea los archivos de entorno locales con secretos aleatorios.
# No pisa archivos existentes ni muestra los valores.
$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$utf8 = New-Object System.Text.UTF8Encoding($false)

function New-RandomBytes([int]$Count) {
    $bytes = New-Object byte[] $Count
    $rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
    $rng.GetBytes($bytes)
    $rng.Dispose()
    return ,$bytes
}

function New-Secret([int]$Count = 24) {
    return -join ((New-RandomBytes $Count) | ForEach-Object { $_.ToString('x2') })
}

function Get-EnvValue([string]$Path, [string]$Key) {
    foreach ($line in [System.IO.File]::ReadAllLines($Path)) {
        if ($line.StartsWith("$Key=")) { return $line.Substring($Key.Length + 1) }
    }
    return ''
}

function New-EnvFile([string]$Example, [string]$Target, [hashtable]$Values) {
    if (Test-Path $Target) {
        Write-Host "Ya existe, no se modifica: $Target"
        return
    }
    $lines = [System.IO.File]::ReadAllLines($Example) | ForEach-Object {
        $key = ($_ -split '=', 2)[0]
        if ($Values.ContainsKey($key)) { "$key=$($Values[$key])" } else { $_ }
    }
    [System.IO.File]::WriteAllText($Target, (($lines -join "`n") + "`n"), $utf8)
    Write-Host "Creado: $Target"
}

$rootEnv = Join-Path $root '.env'
New-EnvFile (Join-Path $root '.env.example') $rootEnv @{
    DB_PASSWORD      = New-Secret
    DB_ROOT_PASSWORD = New-Secret
}

$backendUrl = "http://localhost:$(Get-EnvValue $rootEnv 'BACKEND_PORT')"
$frontendUrl = "http://localhost:$(Get-EnvValue $rootEnv 'FRONTEND_PORT')"

New-EnvFile (Join-Path $root 'backend/.env.example') (Join-Path $root 'backend/.env') @{
    APP_KEY             = 'base64:' + [Convert]::ToBase64String((New-RandomBytes 32))
    JWT_SECRET          = New-Secret 32
    SEED_USERS_PASSWORD = New-Secret 10
    DB_DATABASE         = Get-EnvValue $rootEnv 'DB_DATABASE'
    DB_USERNAME         = Get-EnvValue $rootEnv 'DB_USERNAME'
    DB_PASSWORD         = Get-EnvValue $rootEnv 'DB_PASSWORD'
    APP_URL             = $backendUrl
    APP_URL_FIX_IMAGES  = $backendUrl
    APP_WEB_URL         = $frontendUrl
}

New-EnvFile (Join-Path $root 'frontend/.env.example') (Join-Path $root 'frontend/.env.local') @{
    REACT_APP_API_URL = "$backendUrl/api/"
}
