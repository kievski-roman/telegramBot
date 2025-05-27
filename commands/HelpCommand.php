<?php

require_once __DIR__ . '/../BotCommandInterface.php';

class HelpCommand implements BotCommandInterface
{
    public function execute(string $text): string
    {
        return "Доступні команди:\n/start — запустити бота\n/help — допомога \n/add додати задачу \n/list показати список задач";
    }
}