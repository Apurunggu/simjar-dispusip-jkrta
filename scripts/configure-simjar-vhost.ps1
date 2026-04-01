param(
    [string]$VHostName = 'simjar.test',
    [string]$ProjectPath = 'C:/xampp/htdocs/Simjar_dispusip'
)

function Require-Admin {
    $current = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
    if (-not $current.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
        Write-Error 'Script harus dijalankan sebagai Administrator. Tutup dan jalankan PowerShell sebagai Administrator.'
        exit 1
    }
}

Require-Admin

$httpdVhosts = 'C:/xampp/apache/conf/extra/httpd-vhosts.conf'
$httpdConf = 'C:/xampp/apache/conf/httpd.conf'
$hostsFile = 'C:/Windows/System32/drivers/etc/hosts'
$vhostBlock = @"
<VirtualHost *:80>
    ServerName $VHostName
    DocumentRoot "$ProjectPath/public"
    <Directory "$ProjectPath/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
"@

Write-Host "1) Memeriksa file konfigurasi Apache..."
if (-not (Test-Path $httpdVhosts)) {
    Write-Error "File httpd-vhosts.conf tidak ditemukan di $httpdVhosts. Pastikan XAMPP terinstal di C:/xampp"
    exit 1
}

# Backup
$time = (Get-Date).ToString('yyyyMMddHHmmss')
Copy-Item $httpdVhosts "$httpdVhosts.bak.$time" -Force
Write-Host "Backup httpd-vhosts.conf -> $httpdVhosts.bak.$time"

# Tambah include di httpd.conf jika dikomentari
if (Test-Path $httpdConf) {
    $content = Get-Content $httpdConf
    $includeLine = 'Include conf/extra/httpd-vhosts.conf'
    if ($content -match '#\s*' + [regex]::Escape($includeLine)) {
        Write-Host 'Meng-uncomment Include conf/extra/httpd-vhosts.conf di httpd.conf'
        (Get-Content $httpdConf) -replace '#\s*' + [regex]::Escape($includeLine), $includeLine | Set-Content $httpdConf -Force
    }
}

# Tambahkan virtualhost jika belum ada
$vhContent = Get-Content $httpdVhosts -Raw
if ($vhContent -match [regex]::Escape("ServerName $VHostName")) {
    Write-Host "VirtualHost untuk $VHostName sudah ada di $httpdVhosts"
} else {
    Add-Content $httpdVhosts "`n# VirtualHost for $VHostName - added by script $time`n"
    Add-Content $httpdVhosts $vhostBlock
    Write-Host "VirtualHost ditambahkan ke $httpdVhosts"
}

# Update hosts file
$hostsEntry = "127.0.0.1`t$VHostName"
$hostsText = Get-Content $hostsFile -ErrorAction SilentlyContinue
if ($hostsText -and ($hostsText -contains $hostsEntry)) {
    Write-Host "Hosts entry sudah ada: $hostsEntry"
} else {
    Write-Host "Menambahkan $hostsEntry ke $hostsFile"
    Add-Content -Path $hostsFile -Value "`n# simjar virtual host added $time`n$hostsEntry"
}

# Restart Apache (XAMPP)
$stopBat = 'C:/xampp/apache_stop.bat'
$startBat = 'C:/xampp/apache_start.bat'
if ((Test-Path $stopBat) -and (Test-Path $startBat)) {
    Write-Host 'Menggunakan skrip XAMPP untuk merestart Apache...'
    & $stopBat
    Start-Sleep -Seconds 2
    & $startBat
    Write-Host 'Perintah restart XAMPP dijalankan.'
} else {
    # coba restart service umum
    $services = @('Apache2.4','httpd')
    $restarted = $false
    foreach ($s in $services) {
        $svc = Get-Service -Name $s -ErrorAction SilentlyContinue
        if ($svc) {
            Write-Host "Merestart service: $s"
            Restart-Service -Name $s -Force -ErrorAction Stop
            $restarted = $true
            break
        }
    }
    if (-not $restarted) {
        Write-Warning 'Tidak menemukan skrip XAMPP atau service Apache. Silakan restart Apache lewat XAMPP Control Panel secara manual.'
    } else {
        Write-Host 'Service Apache direstart.'
    }
}

Write-Host "Selesai. Silakan buka: http://$VHostName/barang-masuk/import"
