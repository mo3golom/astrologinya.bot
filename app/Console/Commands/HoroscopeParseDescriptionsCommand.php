<?php

namespace App\Console\Commands;

use App\Models\HoroscopeModel;
use App\Repository\HoroscopeRepository;
use App\Service\OrakulParserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HoroscopeParseDescriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horoscope:parse:descriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг описаний гороскопа и добавление записи в таблицу Horoscope';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param HoroscopeRepository $horoscopeRepository
     * @param OrakulParserService $orakulParserService
     */
    public function handle(HoroscopeRepository $horoscopeRepository, OrakulParserService $orakulParserService): void
    {
        $horoscopeCollection = $horoscopeRepository->getAllWithoutActualHoroscope();

        if (0 >= $horoscopeCollection->count()) {
            $this->info('нет гороскопов, которые нужно обновить');
            Log::info('нет гороскопов, которые нужно обновить');

            return;
        }

        foreach ($horoscopeCollection as $horoscope) {
            if (null !== $horoscope->description_parse_url) {
                /** @var HoroscopeModel $horoscope */
                $description = $orakulParserService->parse($horoscope->description_parse_url);

                $horoscopeRepository->update($horoscope, [
                    'description' => $description,
                ]);

                // Делаем задержку, чтобы сильно не спамить при парсинге
                time_nanosleep(10, 0);
            }
        }
    }
}
