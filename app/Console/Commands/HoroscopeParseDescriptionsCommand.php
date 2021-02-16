<?php

namespace App\Console\Commands;

use App\Models\HoroscopeModel;
use App\Repository\CreativeRepository;
use App\Repository\HoroscopeRepository;
use App\Service\CreativesService;
use App\Service\OrakulParserService;
use Carbon\Carbon;
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
     * @param HoroscopeRepository $horoscopeRepository
     * @param OrakulParserService $orakulParserService
     */
    public function handle(HoroscopeRepository $horoscopeRepository, OrakulParserService $orakulParserService, CreativesService $creativesService): void
    {
        $horoscopeCollection = $horoscopeRepository->getAllWithoutActualHoroscope();

        if (0 >= $horoscopeCollection->count()) {
            $this->info('нет гороскопов, которые нужно обновить');
            Log::info('нет гороскопов, которые нужно обновить');

            return;
        }

        $updateIds = [];
        foreach ($horoscopeCollection as $horoscope) {
            /** @var HoroscopeModel $horoscope */
            if (isset($horoscope->description_parse_url)) {
                $description = $orakulParserService->parse($horoscope->description_parse_url);

                $updateIds[] = $horoscope->horoscope_id;
                $horoscopeRepository->update($horoscope, [
                    'description' => $description,
                ]);

                // Делаем задержку, чтобы сильно не спамить при парсинге
                time_nanosleep(10, 0);
            }
        }

        // Удаляем все ранее сделанные креативы
        $creativesService->deleteByObjectNameAndObjectIds(HoroscopeModel::class, $updateIds);
    }
}
