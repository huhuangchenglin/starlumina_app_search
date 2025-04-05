<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$engine = $_GET['engine'] ?? 'baidu';
$query = $_GET['q'] ?? '';

error_log('Suggest API called with engine: ' . $engine . ' and query: ' . $query);

$suggestApis = [
    'baidu' => [
        'url' => 'https://www.baidu.com/sugrec',
        'params' => ['prod' => 'pc', 'wd' => $query, 'ie' => 'utf-8', 'json' => 1],
        'headers' => ['Host: www.baidu.com', 'Referer: https://www.baidu.com']
    ],
    'google' => [
        'url' => 'https://suggestqueries.google.com/complete/search',
        'params' => ['client' => 'firefox', 'q' => $query, 'hl' => 'zh-CN'],
        'response_key' => 1 // 返回结果中第2个数组是联想词
    ],
    'bing' => [
        'url' => 'https://api.bing.com/qsonhs.aspx',
        'params' => ['q' => $query, 'type' => 'cb'],
        'response_key' => 'AS.Results[0].Suggests'
    ],
    'quark' => [
        'url' => 'https://suggest.sm.cn/s',
        'params' => ['q' => $query, 'callback' => 'jsonp'],
        'headers' => ['Referer: https://quark.sm.cn']
    ]
];

$config = $suggestApis[$engine] ?? [];
if (empty($config) || empty($query)) {
    error_log('Empty config or query, returning empty array');
    echo json_encode([]);
    exit;
}

$url = $config['url'] . '?' . http_build_query($config['params']);
$options = [
    'http' => [
        'method' => 'GET',
        'header' => implode("\r\n", $config['headers'] ?? []) . "\r\n" .
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n" .
                    "Accept: */*\r\n" .
                    "Accept-Language: en-US,en;q=0.9\r\n" .
                    "DNT: 1\r\n" .
                    "Connection: keep-alive\r\n"
    ]
];

error_log('Request URL: ' . $url);
error_log('Request headers: ' . $options['http']['header']);

$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

error_log('Received response: ' . $response);
error_log('Response headers: ' . json_encode($http_response_header));

// 解析不同引擎的响应
$suggestions = [];
if ($response !== false) {
    switch ($engine) {
        case 'baidu':
            $data = json_decode($response, true);
            $suggestions = array_column($data['g'] ?? [], 'q');
            break;
        case 'google':
            $data = json_decode(mb_convert_encoding($response, 'UTF-8', 'ISO-8859-1'), true);
            $suggestions = $data[$config['response_key']] ?? [];
            break;
        case 'bing':
            $data = json_decode(str_replace(['cb(', ')'], '', $response), true);
            $suggestions = array_column($data['AS']['Results'][0]['Suggests'] ?? [], 'Txt');
            break;
        case 'quark':
            preg_match('/jsonp\((.*?)\)/', $response, $matches);
            $suggestions = $matches[1] ? json_decode($matches[1], true) : [];
            break;
    }
} else {
    $error = error_get_last();
    error_log('Failed to get response from: ' . $url . '. Error: ' . json_encode($error));
}

$result = json_encode(array_slice($suggestions, 0, 10));
error_log('Sending suggestions: ' . $result);
echo $result;

// 刷新输出缓冲并发送所有未发送的头部
flush();
ob_flush();