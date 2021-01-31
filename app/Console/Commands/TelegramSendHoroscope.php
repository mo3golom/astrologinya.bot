<?php

namespace App\Console\Commands;

use App\Models\HoroscopeModel;
use App\Repository\HoroscopeRepository;
use App\Service\Botman\TelegramChannel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TelegramSendHoroscope extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:send:horoscope';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг описаний гороскопа и добавление записи в таблицу Horoscope';

    /**
     * Execute the console command.
     *
     * @param HoroscopeRepository $horoscopeRepository
     * @param TelegramChannel $telegramBot
     * @return void
     */
    public function handle(HoroscopeRepository $horoscopeRepository, TelegramChannel $telegramBot): void
    {
        $horoscopes = $horoscopeRepository->getAllNoSend();

        $horoscopes->map(static function (HoroscopeModel $horoscope) use ($horoscopeRepository, $telegramBot) {
            $now = Carbon::now()->setTimezone('Europe/Moscow');
            $sendTime = $horoscope->setting->send_time->copy()->setDateFrom($now);

            if (
                null !== $horoscope->render_template
                && $sendTime->isBefore($now)
            ) {
                $messageId = $telegramBot->sendSilentMessage($horoscope->render_template);

                if (null !== $messageId) {
                    $horoscopeRepository->update($horoscope, [
                        'message_id' => $messageId,
                        'send_at' => Carbon::now(),
                    ]);
                }
            }
        });
    }
}
