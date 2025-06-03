<?php

namespace App\Commands;
use App\Interfaces\BotCommandInterface;
use App\Services\GamePlainService;

class PlainCommand implements BotCommandInterface
{
    public function execute(string $text): string
    {
        $query = trim(str_replace('/plain', '', $text));
        if (!$query) return "Введи назву гри: /plain cyberpunk";

        $slug = (new GamePlainService())->getPlain($query);
        return $slug
            ? "Plain-код гри **$query**:\n`$slug`"
            : "Не знайшов plain";
    }
}