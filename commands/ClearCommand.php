<?php

require_once __DIR__ . '/../BotCommandInterface.php';

class ClearCommand implements BotCommandInterface
{
    public function execute(string $text): string {
        $file = 'storage/tasks.json';

        if (!file_exists($file)) {
            return "⚠️ Список задач і так порожній.";
        }

        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return "🧹 Усі задачі було успішно очищено.";
    }
}