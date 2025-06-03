<?php

namespace App;

use App\CommandFactory;

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env');
    foreach ($lines as $line) {
        if (trim($line) && strpos($line, '=') !== false) {
            [$key, $value] = explode('=', trim($line), 2);
            $_ENV[$key] = $value;
        }
    }
}

class Bot
{
    protected CommandFactory $factory;
    protected string $token;

    public function __construct()
    {
        $this->factory = new CommandFactory();
        $this->token = $_ENV['BOT_TOKEN'];
    }

    public function handle(): void
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $text = $data['message']['text'] ?? '';
        $chatId = $data['message']['chat']['id'] ?? '';

        if (!$text || !$chatId) return;

        // Обробка кнопки "Знайти гру"
        if ($text === '🎮 Знайти гру') {
            file_put_contents(
                __DIR__ . '/../storage/session_' . $chatId . '.json',
                json_encode(['awaiting_game_name' => true])
            );
            $this->sendMessage($chatId, "Введи назву гри:");
            return;
        }
        // Якщо чекаємо назву гри
        $sessionFile = __DIR__ . '/../storage/session_' . $chatId . '.json';
        if (file_exists($sessionFile)) {
            $sessionData = json_decode(file_get_contents($sessionFile), true);

            if (!empty($sessionData['awaiting_game_name'])) {
                unlink($sessionFile); // очищення
                $query = '/deal ' . $text;
                $command = $this->factory->make('/deal', $this);
                $command->handle($chatId, $query);
                return;
            }
        }

        if($text === '🔥 Топ знижки'){
            $text = '/top';
        }
        $callback = $data['callback_query']['data'] ?? null;
        if ($callback) {
            $chatId = $data['callback_query']['message']['chat']['id'];
            $command = $this->factory->make($callback, $this);
            $command->handle($chatId, $callback);
            return;
        }



        // Якщо звичайна команда
        $command = $this->factory->make($text, $this);
        $command->handle($chatId, $text);

    }

    public function sendMessage($chatId, $text, $extra = [])
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        $data = array_merge([
            'chat_id' => $chatId,
            'text' => $text
        ], $extra);

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type:application/x-www-form-urlencoded",
                'content' => http_build_query($data)
            ]
        ];

        file_get_contents($url, false, stream_context_create($options));
    }


    public function sendPhoto($chatId, $photoUrl, $caption = null, $extra = [])
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendPhoto";

        $data = array_merge([
            'chat_id' => $chatId,
            'photo' => $photoUrl,
            'caption' => $caption,
        ], $extra);

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type:application/x-www-form-urlencoded",
                'content' => http_build_query($data)
            ]
        ];

        file_get_contents($url, false, stream_context_create($options));
    }
}




