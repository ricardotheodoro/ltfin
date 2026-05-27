$path = 'vendor/spatie/laravel-package-tools/src/PackageServiceProvider.php'
$content = Get-Content -Raw -LiteralPath $path
$old = 'database_path("${migrationsPath}*.php")'
$new = 'database_path("{$migrationsPath}*.php")'
$content = $content.Replace($old, $new)
[System.IO.File]::WriteAllText((Resolve-Path $path).Path, $content, (New-Object System.Text.UTF8Encoding($false)))
Write-Host "patched $path"
