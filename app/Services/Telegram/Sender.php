<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Telegram\Bot\Api;

class Sender
{
    public static function send(Api $api, array $params): void
    {
        try {
            $api->sendMessage($params);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
