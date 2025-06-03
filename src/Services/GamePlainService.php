<?php


namespace App\Services;

class GamePlainService
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

    public function getPlain(string $title): ?string
    {
        $url  = "https://api.isthereanydeal.com/games/search/v1"
            . "?key={$this->apiKey}&title=" . urlencode(trim($title));

        $data = json_decode(file_get_contents($url), true);

        /* if return
      [
        0 => [
          'id'   => '018d937f-…',          // UUID
          'slug' => 'red-dead-redemption-2',// ← plain
          'title'=> 'Red Dead Redemption 2',
          'assets' => [ 'boxart' => 'https://…' ]
        ],
        1 => [ … ], …
      ]
      */
        return $data[0]['title'] ?? null;
    }
}
