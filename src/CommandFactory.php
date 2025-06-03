<?php

namespace App;

use App\Bot;
use App\Interfaces\BotCommandInterface;
use App\Commands\StartCommand;
use App\Commands\DealCommand;
use App\Commands\HelpCommand;
use App\Commands\ClearCommand;
use App\Commands\PlainCommand;
use App\Commands\TopDealsCommand;

class CommandFactory
{
    public function make(string $type, Bot $bot): BotCommandInterface
    {
        return match (true) {
            str_starts_with($type, "/start") => new StartCommand($bot),
            str_starts_with($type, "/deal") => new DealCommand($bot),
            str_starts_with($type, "/help") => new HelpCommand($bot),
            str_starts_with($type, "/clear") => new ClearCommand($bot),
            str_starts_with($type, "/plain") => new PlainCommand($bot),
            str_starts_with($type, "/top") => new TopDealsCommand($bot),

            default => new HelpCommand(),
        };
    }

}
