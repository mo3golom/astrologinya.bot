<?php

namespace App\Console\Commands;

use App\Models\HoroscopeModel;
use App\Repository\HoroscopeRepository;
use App\Service\ZodiacVideoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Orchid\Attachment\Models\Attachment;

class ZodiacGenerateVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zodiac:generate:video {--horoscope_id=0 : ID гороскопа}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    private $disk;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->disk = config('zodiac.default_disk');
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
        $horoscopeId = (int) $this->option('horoscope_id');

        // Если нужно сгенерировать видео для определенного гороскопа
        if (0 !== $horoscopeId) {
            /** @var HoroscopeModel $horoscope */
            $horoscope = $horoscopeRepository->find($horoscopeId);

            if (null !== $horoscope->video_id) {
                $horoscope->attachment->delete();
            }
        } else {
            /** @var HoroscopeModel $horoscope */
            $horoscope = $horoscopeRepository->getFirstWithoutVideo();
        }

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

        try {
            /** @var Attachment $attachment */
            $attachment = $zodiacVideoGeneratorService->generate(
                $horoscope->setting->template_video_url,
                $horoscope->short_description,
                $horoscope->setting->zodiac,
                $this->disk
            );

            $horoscopeRepository->update(
                $horoscope,
                ['video_id' => $attachment->id]
            );
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
            Log::error($th->getMessage() . $th->getTraceAsString());
        }
    }
}
