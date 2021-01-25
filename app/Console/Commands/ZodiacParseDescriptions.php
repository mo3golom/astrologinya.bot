<?php

namespace App\Console\Commands;

use App\Models\HoroscopeSettingModel;
use App\Repository\HoroscopeRepository;
use App\Repository\HoroscopeSettingRepository;
use App\Service\OrakulParserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ZodiacParseDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zodiac:parse:descriptions';

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
     * Execute the console command.
     *
     * @param HoroscopeSettingRepository $horoscopeSettingRepository
     * @param OrakulParserService $orakulParserService
     * @param HoroscopeRepository $horoscopeRepository
     * @return void
     */
    public function handle(HoroscopeSettingRepository $horoscopeSettingRepository, OrakulParserService $orakulParserService, HoroscopeRepository $horoscopeRepository): void
    {
        /** @var HoroscopeSettingModel|null $horoscopeSetting */
        $horoscopeSetting = $horoscopeSettingRepository->getWithoutActualHoroscope();

        if (null === $horoscopeSetting) {
            $this->info('нет гороскопов, которые нужно обновить');
            Log::info('нет гороскопов, которые нужно обновить');

            return;
        }

        $description = $orakulParserService->parse($horoscopeSetting->parse_url);
        $shortDescription = null;

        if (null !== $horoscopeSetting->short_description_parse_url) {
            $shortDescription = $orakulParserService->parse($horoscopeSetting->short_description_parse_url);
        }

        $horoscopeRepository->deleteByHoroscopeSettingId($horoscopeSetting->horoscope_setting_id);
        $horoscopeRepository->create([
            'horoscope_setting_id' => $horoscopeSetting->horoscope_setting_id,
            'description' => $description,
            'short_description' => $shortDescription,
        ]);
    }
}
