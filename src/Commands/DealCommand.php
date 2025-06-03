<?php

namespace App\Commands;

use App\Bot;
use App\Interfaces\BotCommandInterface;
use App\Services\GamePriceService;

class DealCommand implements BotCommandInterface
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function handle($chatId, $text): void
    {
        $title = str_starts_with($text, '/deal')
            ? trim(str_replace('/deal', '', $text))
            : trim($text);

        if (!$title) {
            $this->bot->sendMessage($chatId, "Введи назву гри: doom eternal");
            return;
        }

        $data = (new GamePriceService())->onePriceByTitle($title);

        if (!$data) {
            $this->bot->sendMessage($chatId, "Немає знижок для '{$title}' 🫤");
            return;
        }

        $msg = "🎮 *{$title}*\n";
        $msg .= "🛒 Магазин: {$data['shop']['name']}\n";
        $msg .= "💸 {$data['regular']['amount']} → {$data['price']['amount']} USD (-{$data['cut']}%)\n";
        $msg .= "[Купити тут]({$data['url']})";

        $this->bot->sendMessage($chatId, $msg, ['parse_mode' => 'Markdown']);
    }
}





