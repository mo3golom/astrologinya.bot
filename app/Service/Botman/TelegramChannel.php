<?php

declare(strict_types=1);

namespace App\Service\Botman;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TelegramChannel
{
    private $channelId;
    /**
     * @var BotMan
     */
    private $bot;

    public function __construct()
    {
        $this->channelId = config('services.telegram.channel_id');
        $this->bot = BotManFactory::create(config('botman'));
    }

    /**
     * @param string $message
     * @return Response|null
     */
    public function sendMessage(string $message): ?Response
    {
        try {
            return $this->bot->say($message, $this->channelId, TelegramDriver::class);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . $th->getTraceAsString());
        }

        return null;
    }
}