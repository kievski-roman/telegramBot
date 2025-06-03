<?php
namespace App\Services;

class GameTopDealService
{
    private string $apiKey;
    public function __construct()
    {
        if (file_exists(__DIR__ . '/../../.env')) {
            $lines = file(__DIR__ . '/../../.env');
            foreach ($lines as $line) {
                if (trim($line) && strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', trim($line), 2);
                    $_ENV[$key] = $value;
                }
            }
        }

        $this->apiKey = $_ENV['IS_THERE_ANY_DEAL_KEY'] ?? '';
    }

    public function getTopDeals(int $limit = 50): ?array
    {
        $cacheFile = __DIR__ . '/../../storage/top_deals_cache.json';

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 30) {
            return json_decode(file_get_contents($cacheFile), true);
        }

        $url = "https://api.isthereanydeal.com/deals/v2?key={$this->apiKey}&limit={$limit}";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        usleep(200000); // optional
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            file_put_contents(__DIR__ . '/../../log_get_deals.txt', "❌ CURL failed\n", FILE_APPEND);
            return null;
        }

        $data = json_decode($response, true);

        // 🛑 ВАЖЛИВО: перевіряємо просто $data['list']
        if (!isset($data['list']) || !is_array($data['list'])) {
            file_put_contents(__DIR__ . '/../../log_get_deals.txt', "⚠️ Немає list або не масив:\n$response\n", FILE_APPEND);
            return null;
        }

        file_put_contents($cacheFile, json_encode($data['list']));
        return $data['list'];
    }
}