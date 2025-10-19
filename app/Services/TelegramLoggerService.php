<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramLoggerService
{
    public function incomingUpdate($update)
    {
        Log::channel('telegram')->info('📨 INCOMING UPDATE', [
            'update_id' => $update->getUpdateId(),
            'type' => $this->getUpdateType($update),
            'chat_id' => $this->getChatId($update),
            'text' => $this->getMessageText($update),
            'data' => $update->toArray()
        ]);
    }

    public function outgoingMessage($chatId, $text, $response = null)
    {
        Log::channel('telegram')->info('📤 OUTGOING MESSAGE', [
            'chat_id' => $chatId,
            'text' => $text,
            'response' => $response ? $response->toArray() : null
        ]);
    }

    public function error($error, $context = [])
    {
        Log::channel('telegram_errors')->error('❌ TELEGRAM ERROR', [
            'error' => $error,
            'context' => $context,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ]);
    }

    public function debug($message, $context = [])
    {
        Log::channel('telegram_debug')->debug('🔍 DEBUG: ' . $message, $context);
    }

    public function buttonClick($chatId, $buttonData)
    {
        Log::channel('telegram')->info('🔄 BUTTON CLICK', [
            'chat_id' => $chatId,
            'button_data' => $buttonData,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    private function getUpdateType($update)
    {
        if ($update->has('message')) return 'message';
        if ($update->has('callback_query')) return 'callback_query';
        if ($update->has('inline_query')) return 'inline_query';
        if ($update->has('edited_message')) return 'edited_message';
        return 'unknown';
    }

    private function getChatId($update)
    {
        if ($update->has('message')) {
            return $update->getMessage()->getChat()->getId();
        }
        if ($update->has('callback_query')) {
            return $update->getCallbackQuery()->getMessage()->getChat()->getId();
        }
        return null;
    }

    private function getMessageText($update)
    {
        if ($update->has('message') && $update->getMessage()->has('text')) {
            return $update->getMessage()->getText();
        }
        if ($update->has('callback_query')) {
            return $update->getCallbackQuery()->getData();
        }
        return null;
    }
}
