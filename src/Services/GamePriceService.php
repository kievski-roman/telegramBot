<?php
namespace App\Services;

class GamePriceService
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
    public function onePriceByTitle(string $title): ?array
    {
        /* 1. пошук uuid */
        $searchUrl = "https://api.isthereanydeal.com/games/search/v1"
            . "?key={$this->apiKey}&title=" . urlencode(trim($title));

        $uuid = json_decode(file_get_contents($searchUrl), true)[0]['id'] ?? null;
        if (!$uuid) return null;

        /* 2. overview через cURL */
        $body = json_encode([$uuid]);
        $ch   = curl_init("https://api.isthereanydeal.com/games/overview/v2?key={$this->apiKey}&country=UA");
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
        ]);
        curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if (!$resp) { file_put_contents('curl_err.log', $err); return null; }

        $data = json_decode($resp, true);
        return $data['prices'][0]['current'] ?? null;
    }
}
