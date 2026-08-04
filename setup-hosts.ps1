# Script to configure local hostnames for Fast Order Multi-Tenant Local Development
# MUST BE RUN AS ADMINISTRATOR

$HostsPath = "C:\Windows\System32\drivers\etc\hosts"

$Entries = @(
    "127.0.0.1 fastorder.test",
    "127.0.0.1 app.fastorder.test",
    "127.0.0.1 tenant1.fastorder.test",
    "127.0.0.1 tenant2.fastorder.test",
    "127.0.0.1 demo.fastorder.test"
)

# Check for administrative privileges
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Warning "This script must be run as Administrator to modify the hosts file."
    Write-Host ""
    Write-Host "Please open PowerShell as Administrator and run this script again."
    Write-Host "Alternatively, manually add the following lines to your '$HostsPath' file:"
    Write-Host "--------------------------------------------------"
    foreach ($Entry in $Entries) {
        Write-Host "  $Entry"
    }
    Write-Host "--------------------------------------------------"
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit
}

Write-Host "Checking hosts file at '$HostsPath'..."

$content = Get-Content $HostsPath

$changesMade = $false

foreach ($Entry in $Entries) {
    # Check if the entry already exists
    if ($content -match [regex]::Escape($Entry)) {
        Write-Host "Entry already exists: $Entry"
    } else {
        Write-Host "Adding entry: $Entry"
        Add-Content -Path $HostsPath -Value "`n$Entry" -Encoding utf8
        $changesMade = $true
    }
}

if ($changesMade) {
    Write-Host "Hosts file updated successfully!"
} else {
    Write-Host "No changes were needed."
}

Write-Host ""
Write-Host "To test, run 'php artisan serve' and visit:"
Write-Host " - Landing Page: http://fastorder.test:8000"
Write-Host " - Super Admin: http://app.fastorder.test:8000"
Write-Host " - Tenant 1 Storefront: http://tenant1.fastorder.test:8000"
Write-Host " - Tenant 1 Admin: http://tenant1.fastorder.test:8000/admin"
Write-Host ""

Read-Host "Press Enter to exit"
