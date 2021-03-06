<?php

include_once __DIR__.'/vendor/autoload.php';

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SetWebhook;
use function GuzzleHttp\Psr7\str;

$bot = new Bot(getenv('TELEGRAM_TOKEN'));

$response = $bot->getWebhookInfo(
    new SetWebhook($_SERVER['argv'][1])
);

echo str($response).PHP_EOL;
