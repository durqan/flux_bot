<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class SetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram webhook URL';

    /**
     * Execute the console command.
     * @throws TelegramSDKException
     */
    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        $url = env('TELEGRAM_BOT_WEBHOOK_URL');

        $response = $telegram->setWebhook(['url' => $url]);

        if ($response) {
            $this->info('Webhook установлен успешно: ' . $url);
        } else {
            $this->error('Ошибка установки webhook');
        }
    }
}
