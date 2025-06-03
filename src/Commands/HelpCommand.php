<?php

namespace App\Commands;
use App\Interfaces\BotCommandInterface;



class HelpCommand implements BotCommandInterface
{
    public function execute(string $text): string
    {
        return "Доступні команди:\n/start — запустити бота\n/help — допомога \n/deal 'name game' - пошук гри \n/top - вивід топ 5 кращих знижок з всіх магазинів\n";
    }
}