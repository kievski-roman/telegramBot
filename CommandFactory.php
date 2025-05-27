<?php
require_once 'commands/StartCommand.php';
require_once 'commands/HelpCommand.php';
require_once 'commands/AddCommand.php';
require_once 'commands/ListCommand.php';
require_once 'commands/DeleteCommand.php';
require_once 'commands/DoneCommand.php';
require_once 'commands/ClearCommand.php';
class CommandFactory
{
    public function make(string $type): BotCommandInterface
    {
        return match (true) {
          str_starts_with($type, "/start")=> new StartCommand(),
          str_starts_with($type, "/help")=> new HelpCommand(),
          str_starts_with($type, "/add")=> new AddCommand(),
            str_starts_with($type, "/list")=> new ListCommand(),
            str_starts_with($type, '/delete') => new DeleteCommand(),
            str_starts_with($type, '/done') => new DoneCommand(),
            str_starts_with($type, '/clear') => new ClearCommand(),
            default => new HelpCommand(),
        };
    }
}