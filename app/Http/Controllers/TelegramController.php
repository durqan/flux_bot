<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramController extends Controller
{
    protected Api $telegram;

    /**
     * @throws TelegramSDKException
     */
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    /**
     * @throws TelegramSDKException
     */
    public function webhook(): JsonResponse
    {
        $update = $this->telegram->getWebhookUpdate();

        if ($update->has('message')) {
            $this->handleMessage($update->getMessage());
        }

        if ($update->has('callback_query')) {
            $this->handleCallbackQuery($update->callbackQuery);
        }

        return response()->json(['status' => 'success']);
    }

    private function handleMessage($message): void
    {
        $chatId = $message->getChat()->getId();
        $text = $message->getText();

        switch ($text) {
            case '/start':
                $this->sendWelcomeMessage($chatId);
                break;
            default:
                $this->handleRegularMessage($chatId, $text);
        }
    }

    /**
     * @throws TelegramSDKException
     */
    private function sendWelcomeMessage($chatId): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Добро пожаловать! Я бот на Laravel!',
            'parse_mode' => 'HTML'
        ]);
    }

    /**
     * @throws TelegramSDKException
     */
    private function handleCallbackQuery($callbackQuery): void
    {
        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $data = $callbackQuery->getData();


        // Обработка нажатий на кнопки
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Вы нажали: {$data}"
        ]);
    }

    public function handleRegularMessage($chatId, $text): void
    {
        $telegramService = new TelegramService();

        $telegramService->sendMessage(
            $chatId,
            "Вы написали: {$text}"
        );
    }
}
