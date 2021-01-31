<?php

declare(strict_types=1);

namespace App\Service\Botman;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Messages\Attachments\Attachment;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;

class TelegramChannel
{
    /**
     * @var string
     */
    private $channelId;

    /**
     * @var BotMan
     */
    private $bot;

    /**
     * @var string
     */
    private $channelUrl;

    public function __construct()
    {
        $this->channelId = config('services.telegram.channel_id');
        $this->channelUrl = config('services.telegram.channel_url');
        $this->bot = BotManFactory::create(config('botman'));
    }

    /**
     * Отправка сообщения
     *
     * @param string $message
     * @param Attachment|null $attachment
     * @param bool $silentMode
     * @return int|null
     */
    public function sendMessage(string $message, Attachment $attachment = null, $silentMode = false): ?int
    {
        try {
            $message = OutgoingMessage::create($message, $attachment);

            $result =  $this->bot->say(
                $message,
                $this->channelId,
                TelegramDriver::class,
                [
                    'disable_notification' => $silentMode,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]
            );
            $content = json_decode($result->getContent(), true, 512, JSON_THROW_ON_ERROR);

            return $content['result']['message_id'] ?? null;
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
     * @return int|null
     */
    public function sendSilentMessage(string $message, Attachment $attachment = null): ?int
    {
        return $this->sendMessage($message, $attachment, true);
    }

    /**
     * @param int $messageId
     * @return string
     */
    public function getUrlByMessageId(int $messageId): string
    {
        return sprintf('%s/%s', $this->channelUrl, $messageId);
    }
}