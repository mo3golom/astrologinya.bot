<?php

declare(strict_types=1);

namespace App\Service\Botman;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Messages\Attachments\Attachment;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
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
     * Отправка сообщения
     *
     * @param string $message
     * @param Attachment|null $attachment
     * @param bool $silentMode
     * @return Response|null
     */
    public function sendMessage(string $message, Attachment $attachment = null, $silentMode = false): ?Response
    {
        try {
            $message = OutgoingMessage::create($message, $attachment);

            return $this->bot->say(
                $message,
                $this->channelId,
                TelegramDriver::class,
                ['disable_notification' => $silentMode]
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . $th->getTraceAsString());
        }

        return null;
    }

    /**
     * Отправка сообщения без звукового уведомления
     *
     * @param string $message
     * @param Attachment|null $attachment
     * @return Response|null
     */
    public function sendSilentMessage(string $message, Attachment $attachment = null): ?Response
    {
        return $this->sendMessage($message, $attachment, true);
    }
}