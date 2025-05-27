<?php
require_once __DIR__ . '/../BotCommandInterface.php';

class ListCommand implements BotCommandInterface
{
    public function execute(string $text): string {
        $file = 'storage/tasks.json';

        if (!file_exists($file)) {
            return "⚠️ Список задач порожній.";
        }

        $tasks = json_decode(file_get_contents($file), true);

        if (empty($tasks)) {
            return "📝 Задач поки немає. Додай одну через /add";
        }

        $output = "📋 Список задач:\n";
//проходимо по декодованому  масиву додаємо +1 і виводим ключ
        foreach ($tasks as $i => $task) {
            $output .= ($i + 1) . ". " . $task . "\n";
            var_dump($i);
        }

        return $output;
    }
}
