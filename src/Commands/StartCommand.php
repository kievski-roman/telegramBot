<?php

namespace App\Commands;
use App\Interfaces\BotCommandInterface;


use App\Bot;

class StartCommand implements BotCommandInterface
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function handle($chatId, $text): void
    {
        file_put_contents(
            __DIR__ . '/../../storage/session_' . $chatId . '.json',
            json_encode(['awaiting_game_name' => true])
        );
        $keyboard = [
            'keyboard' => [['🎮 Знайти гру'], ['🔥 Топ знижки']],
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ];

        $this->bot->sendMessage($chatId, "Натисни на одну з кнопок нижче 👇", [
            'reply_markup' => json_encode($keyboard)
        ]);
    }
}
