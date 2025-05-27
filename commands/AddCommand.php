<?php
require_once __DIR__ . '/../BotCommandInterface.php';

class AddCommand implements BotCommandInterface {
    public function execute(string $text): string {
        // Вирізаємо /add + очищаємо пробіли
        $taskText = trim(str_replace('/add', '', $text));

        if ($taskText === '') {
            return "Напиши задачу після команди /add. Наприклад: /add зробити чай";
        }

        //файл де все буде зберагатись
        $tasksFile = 'storage/tasks.json';

        //якщо файл існує декодуєм і отрумуєм якщо ні пустий масив
        $tasks = file_exists($tasksFile)
            ? json_decode(file_get_contents($tasksFile), true)
            : [];

        $tasks[] = $taskText;
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return "✅ Задача додана: $taskText";
    }
}
