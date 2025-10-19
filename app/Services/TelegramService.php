<?php

namespace App\Services;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramService
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
    public function sendMessage($chatId, $text, $keyboard = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        if ($keyboard) {
            $params['reply_markup'] = $keyboard;
        }

        $this->telegram->sendMessage($params);
    }

    public function createInlineKeyboard($buttons): Keyboard
    {
        $keyboard = Keyboard::make()->inline();

        foreach ($buttons as $row) {
            $keyboardButtons = [];
            foreach ($row as $button) {
                $keyboardButtons[] = Keyboard::inlineButton([
                    'text' => $button['text'],
                    'callback_data' => $button['callback_data']
                ]);
            }
            $keyboard->row(...$keyboardButtons);
        }

        return $keyboard;
    }
}
