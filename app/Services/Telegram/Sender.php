<?php

namespace App\Services\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class Sender
{
    /**
     * @throws TelegramSDKException
     */
    public static function send(Api $api, array $params): void
    {
        $api->sendMessage($params);
    }
}
