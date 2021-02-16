<?php

namespace App\Console\Commands;

use App\Models\CreativeSettingModel;
use App\Repository\CreativeSettingsRepository;
use App\Service\Creatives\CreativeFactory;
use Illuminate\Console\Command;

class CreativeGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'creative:generate  {--creative_setting_id=0 : ID настройки креатива}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск генерации креатива для настройки, у которой еще есть что генерировать';

    /**
     * Execute the console command.
     *
     * @param CreativeSettingsRepository $creativeSettingsRepository
     * @param CreativeFactory $creativeFactory
     * @return void
     */
    public function handle(CreativeSettingsRepository $creativeSettingsRepository, CreativeFactory $creativeFactory): void
    {
        $creativeSettingId = (int) $this->option('creative_setting_id');

        if (0 === $creativeSettingId) {
            /** @var CreativeSettingModel $creativeSetting */
            $creativeSetting = $creativeSettingsRepository->getFirstNotCompleteActiveSetting();
            $creativeSettingId = $creativeSetting->creative_setting_id;
        }

        $manager = $creativeFactory->getManager($creativeSettingId);
        $manager->exec();
    }
}
