<?php

include_once __DIR__.'/vendor/autoload.php';

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Formapro\TelegramBot\SendMessage;

if ('/get_updates' !== $_SERVER['REQUEST_URI']) {
    echo 'Telegram Bot Demo'.PHP_EOL;

    exit;
}

try {
    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

    $update = Update::create($data);

    $bot = new Bot(getenv('TELEGRAM_TOKEN'));
    $bot->sendMessage(new SendMessage(
        $update->getMessage()->getChat()->getId(),
        'Hi there! What can I do?'
    ));

    http_response_code(200);
    echo 'OK'.PHP_EOL;
} catch (\Throwable $th) {
    file_put_contents('php://stderr', (string) $th);

    http_response_code(500);
    echo 'Internal Server Error'.PHP_EOL;
}
