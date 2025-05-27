<?php
require_once __DIR__ . '/../BotCommandInterface.php';
class DoneCommand implements BotCommandInterface
{
    public function execute(string $text):string{
        $file = 'storage/tasks.json';
        if (!file_exists($file)) {
            return "⚠️ Список задач порожній.";
        }
        $tasks = json_decode(file_get_contents($file), true);
        if (empty($tasks)) {
            return "📝 Задач поки немає. Додай одну через /add";
        }
        $index = (int) trim(str_replace('/done', '', $text)) - 1;


        if (!isset($tasks[$index])) {
            return "🚫 Немає задачі з номером " . ($index + 1);
        }

        $tasks[$index] = "Виконано" . $tasks[$index];
        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return "Виконано задачу: $tasks[$index]";
    }
}