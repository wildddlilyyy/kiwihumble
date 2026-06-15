$ErrorActionPreference = "Stop"

$root = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path
$port = if ($env:PORT) { [int] $env:PORT } else { 8766 }
$listener = [System.Net.Sockets.TcpListener]::new([System.Net.IPAddress]::Parse("127.0.0.1"), $port)
$listener.Start()

Write-Host "KIWI Humble preview is running at http://127.0.0.1:$port/"
Write-Host "Keep this window open while previewing. Press Ctrl+C to stop."

$types = @{
    ".html" = "text/html; charset=utf-8"
    ".css" = "text/css; charset=utf-8"
    ".js" = "text/javascript; charset=utf-8"
    ".json" = "application/json; charset=utf-8"
    ".svg" = "image/svg+xml"
    ".gif" = "image/gif"
    ".png" = "image/png"
}

while ($true) {
    $client = $listener.AcceptTcpClient()

    try {
        $stream = $client.GetStream()
        $reader = [System.IO.StreamReader]::new($stream, [System.Text.Encoding]::ASCII, $false, 1024, $true)
        $requestLine = $reader.ReadLine()

        while (($line = $reader.ReadLine()) -ne $null -and $line -ne "") {
        }

        if (-not $requestLine) {
            continue
        }

        $parts = $requestLine.Split(" ")
        $urlPath = [System.Uri]::UnescapeDataString($parts[1].Split("?")[0])

        if ($urlPath -eq "/") {
            $urlPath = "/demo/index.html"
        }

        $relativePath = $urlPath.TrimStart("/") -replace "/", [System.IO.Path]::DirectorySeparatorChar
        $filePath = [System.IO.Path]::GetFullPath((Join-Path $root $relativePath))

        if (-not $filePath.StartsWith($root, [System.StringComparison]::OrdinalIgnoreCase) -or -not (Test-Path $filePath -PathType Leaf)) {
            $body = [System.Text.Encoding]::UTF8.GetBytes("Not found")
            $header = "HTTP/1.1 404 Not Found`r`nContent-Length: $($body.Length)`r`nContent-Type: text/plain; charset=utf-8`r`nConnection: close`r`n`r`n"
        } else {
            $body = [System.IO.File]::ReadAllBytes($filePath)
            $extension = [System.IO.Path]::GetExtension($filePath)
            $contentType = if ($types.ContainsKey($extension)) { $types[$extension] } else { "application/octet-stream" }
            $header = "HTTP/1.1 200 OK`r`nContent-Length: $($body.Length)`r`nContent-Type: $contentType`r`nConnection: close`r`n`r`n"
        }

        $headerBytes = [System.Text.Encoding]::ASCII.GetBytes($header)
        $stream.Write($headerBytes, 0, $headerBytes.Length)
        $stream.Write($body, 0, $body.Length)
    } finally {
        $client.Close()
    }
}
