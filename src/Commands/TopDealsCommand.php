<?php
namespace App\Commands;

use App\Bot;
use App\Interfaces\BotCommandInterface;
use App\Services\GameTopDealService;
class TopDealsCommand implements BotCommandInterface{
    private Bot $bot;
    public function __construct(Bot $bot){
        $this->bot = $bot;
    }
    public function handle($chatId, $text): void
    {
        $page = 0;
        if (preg_match('/\/top\s+(\d+)/', $text, $matches)) {
            $page = (int)$matches[1];
        }else{
            $page = 0;
        }
        $perPage = 5;
        $start = $page * $perPage;

        $dealsList = (new GameTopDealService())->getTopDeals(50);
        $dealsList = array_slice($dealsList, $start, $perPage);
        if (!$dealsList) {
            $this->bot->sendMessage($chatId,"😢 Знижок не знайдено.");
            return;
        }
        $msg = '';
        foreach ($dealsList as $dealData) {
            $deal = $dealData['deal'] ?? null;
            if (!$deal) continue;

            $title     = $dealData['title'] ?? 'Невідома гра';
            $store     = $deal['shop']['name'] ?? 'невідомо';
            $oldPrice  = $deal['regular']['amount'] ?? '?';
            $newPrice  = $deal['price']['amount'] ?? '?';
            $cut       = $deal['cut'] ?? 0;
            $url       = $deal['url'] ?? '#';

            $msg .= "🎮 *$title*\n";
            $msg .= "🏪 $store\n";
            $msg .= "💸 $oldPrice ➝ $newPrice USD (-$cut%)\n";
            $msg .= "🔗 [Перейти до пропозиції]($url)\n\n";
        }

        $keyboard = [
            'inline_keyboard' => [[
                ['text' => '👉 Більше', 'callback_data' => "/top " . ($page + 1)]
            ]]
        ];

        $this->bot->sendMessage($chatId, $msg, [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode($keyboard)
        ]);


    }
}
