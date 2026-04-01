# Simple tester to GET pages, check anchor links and form actions
# Usage: Run from repository root with PowerShell (needs the app running at http://127.0.0.1:8000)

$base = 'http://127.0.0.1:8000'
$pages = @('/', '/barang-masuk', '/perangkat-jaringan', '/barang-masuk/create')

function Get-HrefsAndForms {
    param($html)
    $hrefs = [regex]::Matches($html, 'href\s*=\s*"([^"]+)"') | ForEach-Object { $_.Groups[1].Value }
    $actions = [regex]::Matches($html, '<form[^>]*action\s*=\s*"([^"]+)"[^>]*method\s*=\s*"?([A-Za-z]+)"?', 'IgnoreCase') | ForEach-Object { @{ action = $_.Groups[1].Value; method = $_.Groups[2].Value } }
    return @{ hrefs = $hrefs; actions = $actions }
}

$results = @()
foreach ($p in $pages) {
    $url = $base.TrimEnd('/') + $p
    try {
        $resp = Invoke-WebRequest -Uri $url -UseBasicParsing -ErrorAction Stop
        $status = $resp.StatusCode
        $body = $resp.Content
    } catch {
        $status = $_.Exception.Response.StatusCode.Value__ 2>$null
        $body = ''
    }

    $items = Get-HrefsAndForms $body

    Write-Output "\nPage: $url    (HTTP $status)"

    Write-Output " Links:"
    $uniqueLinks = $items.hrefs | Select-Object -Unique | Where-Object { $_ -and ($_ -notmatch '^javascript:') }
    foreach ($l in $uniqueLinks) {
        $full = if ($l -match '^https?://') { $l } elseif ($l -match '^/') { $base.TrimEnd('/') + $l } else { $base.TrimEnd('/') + '/' + $l }
        try {
            $r = Invoke-WebRequest -Uri $full -UseBasicParsing -Method GET -MaximumRedirection 5 -ErrorAction Stop
            $s = $r.StatusCode
        } catch {
            $s = $_.Exception.Response.StatusCode.Value__ 2>$null
        }
        Write-Output ("  [GET] {0,-60} {1}" -f $full, $s)
    }

    Write-Output " Forms:"
    foreach ($f in $items.actions) {
        $a = $f.action
        $m = $f.method.ToUpper()
        if (-not $a) { continue }
        $full = if ($a -match '^https?://') { $a } elseif ($a -match '^/') { $base.TrimEnd('/') + $a } else { $base.TrimEnd('/') + '/' + $a }
        # We'll try a GET to the action to check it exists; for non-GET methods we just report
        try {
            $r = Invoke-WebRequest -Uri $full -UseBasicParsing -Method GET -ErrorAction Stop
            $s = $r.StatusCode
        } catch {
            $s = $_.Exception.Response.StatusCode.Value__ 2>$null
        }
        Write-Output ("  [FORM {0}] {1,-60} {2}" -f $m, $full, $s)
    }
}

Write-Output "\nDone. Review the output above for missing or failing endpoints."
