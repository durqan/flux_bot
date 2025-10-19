<?php

namespace App\Services;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramService
{
    protected Api $telegram;
    protected TelegramLoggerService $logger;

    /**
     * @throws TelegramSDKException
     */
    public function __construct(TelegramLoggerService $logger)
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->logger = $logger;
    }

    /**
     * @throws TelegramSDKException
     */
    public function sendMessage($chatId, $text, $keyboard = null)
    {
        try {
            $params = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ];

            if ($keyboard) {
                $params['reply_markup'] = $keyboard;
            }

            $response = $this->telegram->sendMessage($params);

            $this->logger->outgoingMessage($chatId, $text, $response);

            return $response;

        } catch (TelegramResponseException $e) {
            $this->logger->error('Telegram API Error', [
                'chat_id' => $chatId,
                'text' => $text,
                'error' => $e->getMessage(),
                'response' => $e->getResponse()?->getBody()?->getContents()
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->logger->error('General Error sending message', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
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
