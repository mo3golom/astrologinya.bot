<?php

namespace App\Console\Commands;

use App\Models\HoroscopeModel;
use App\Repository\HoroscopeRepository;
use App\Service\ZodiacVideoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ZodiacGenerateVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zodiac:generate:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param HoroscopeRepository $horoscopeRepository
     * @param ZodiacVideoService $zodiacVideoGeneratorService
     * @return void
     */
    public function handle(HoroscopeRepository $horoscopeRepository, ZodiacVideoService $zodiacVideoGeneratorService): void
    {
        /** @var HoroscopeModel $horoscope */
        $horoscope = $horoscopeRepository->getFirstWithoutVideo();

        if (null === $horoscope) {
            $msg = 'Нет гороскопов без видео';
            Log::info($msg);
            $this->info($msg);

            return;
        }

        if (empty($horoscope->short_description)) {
            $msg = sprintf('нет короткого описания для зодиака %s', $horoscope->setting->zodiac);
            Log::info($msg);
            $this->info($msg);

            return;
        }

        $attachment = $zodiacVideoGeneratorService->generate(
            $horoscope->setting->template_video_path,
            $horoscope->short_description,
            $horoscope->setting->zodiac
        );

        $horoscopeRepository->update(
            $horoscope,
            ['video_id' => $attachment->id]
        );
    }
}
