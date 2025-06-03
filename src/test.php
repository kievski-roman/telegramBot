<?php
//namespace App;
//use App\Services\GamePriceService;
//$result = (new GamePriceService())->onePriceByTitle('red dead redemption 2');
//
//if (!$result) {
//    echo "Немає знижок 😢";
//} else {
//    echo "Магазин: {$result['shop']['name']}\n";
//    echo "Стара:  {$result['regular']['amount']} {$result['regular']['currency']}\n";
//    echo "Нова:   {$result['price']['amount']} {$result['price']['currency']}\n";
//    echo "Знижка: {$result['cut']}%\n";
//    echo "Купити: {$result['url']}\n";
//}

function debugCurl(string $url, array $post = null) {
    $ch = curl_init($url);
    $opts = [CURLOPT_RETURNTRANSFER => true];
    if ($post !== null) {
        $opts += [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ];
    }
    curl_setopt_array($ch, $opts);
    $resp = curl_exec($ch);
    echo "HTTP-code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
    echo "Response:\n" . $resp . PHP_EOL;
    curl_close($ch);
}

