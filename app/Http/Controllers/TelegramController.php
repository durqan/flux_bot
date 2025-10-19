<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    protected Api $telegram;
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }
    public function webhook(): JsonResponse
    {
        $update = $this->telegram->getWebhookUpdate();

        if ($update->has('message')) {
            $this->sendMessage($update->getMessage());
        }

        return response()->json(['status' => 'success']);
    }
    private function sendMessage($message): void
    {
        $chatId = $message->getChat()->getId();
        $text = $message->getText() ?? 'Добро пожаловать! Я бот на Laravel!';

        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];
        $this->telegram->sendMessage($params);
    }
}
