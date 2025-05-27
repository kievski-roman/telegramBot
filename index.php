<?php
require_once 'CommandFactory.php';
$token = trim(file_get_contents(__DIR__ . '/.token'));
$data = json_decode(file_get_contents('php://input'), true);

// декодуємо отримуємо все від тг і вписуємо текст в нашу архітектуру

if (!isset($data['message']['text'])) exit;

$chatId = $data['message']['chat']['id'];
$text = $data['message']['text'];

$factory = new CommandFactory();
$command = $factory->make($text);
$responseText = $command->execute($text);

file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($responseText));
