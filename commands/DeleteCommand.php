<?php
require_once __DIR__ . '/../BotCommandInterface.php';
class DeleteCommand implements BotCommandInterface
{
    public function execute(string $text): string
    {
        $file = 'storage/tasks.json';


        if (!file_exists($file)) {
            return "⚠️ Список задач порожній.";
        }
        $tasks = json_decode(file_get_contents($file), true);
        if (empty($tasks)) {
            return "📝 Задач поки немає. Додай одну через /add";
        }

        //str_replace Видаляє слово /delete зі стрічки те що приходить від $text
        //переводимо в число і -1 встановлюєм правильну послідовність масиву
        $index = (int) trim(str_replace('/delete', '', $text)) - 1;


        if (!isset($tasks[$index])) {
            return "🚫 Немає задачі з номером " . ($index + 1);
        }
        $deleted = $tasks[$index];
        unset($tasks[$index]);

        // Перевпорядкуємо індекси
        $tasks = array_values($tasks);

        file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return "🗑 Видалено задачу: $deleted";

    }
}