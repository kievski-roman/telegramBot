<?php
require_once __DIR__ . '/../BotCommandInterface.php';
class StartCommand implements BotCommandInterface
{
    public function execute(string $text):string{
        return "Напиши /help щоб побачити список команд. ";
    }
}