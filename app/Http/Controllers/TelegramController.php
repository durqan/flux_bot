<?php

namespace App\Http\Controllers;

use App\Services\Telegram\Sender;
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
        $text = $message->getText();

        $params = [];
        $params['chat_id'] = $message->getChat()->getId();
        $params['parse_mode'] = 'HTML';

        $params['text'] = match ($text) {
            '/start' => 'Добро пожаловать в Flux бот!',
            default => 'Ваше сообщение ' . $text,
        };

        Sender::send($this->telegram, $params);
    }
}
