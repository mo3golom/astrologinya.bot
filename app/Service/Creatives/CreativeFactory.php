<?php

declare(strict_types=1);

namespace App\Service\Creatives;

use App\Models\CreativeSettingModel;
use App\Repository\CreativeSettingsRepository;
use App\Service\Creatives\Generators\CreativeGeneratorInterface;
use App\Service\Creatives\Managers\BaseCreativeManager;
use App\Service\Creatives\Managers\CreativeManagerInterface;
use App\Service\Creatives\ObjectGetters\CreativeObjectGetterInterface;

class CreativeFactory
{
    /**
     * @var CreativeSettingsRepository
     */
    private $creativeSettingsRepository;

    public function __construct(CreativeSettingsRepository $creativeSettingsRepository)
    {
        $this->creativeSettingsRepository = $creativeSettingsRepository;
    }

    public function getManager(int $creativeSettingId): CreativeManagerInterface
    {
        /** @var CreativeSettingModel $creativeSetting */
        $creativeSetting = $this->creativeSettingsRepository->find($creativeSettingId);

        /** @var CreativeObjectGetterInterface $objectGetter */
        $objectGetter = app($creativeSetting->object_getter_class);
        $objectGetter->setConfig($creativeSetting->settings);

        /** @var CreativeGeneratorInterface $generator */
        $generator = app($creativeSetting->generator_class);
        $generator->setConfig($creativeSetting->settings);

        /** @var CreativeManagerInterface $manager */
        $manager = app($creative['manager'] ?? BaseCreativeManager::class);

        return
            $manager
                ->setCreativeSettingId($creativeSetting->creative_setting_id)
                ->setObjectGetter($objectGetter)
                ->setGenerator($generator)
            ;
    }
}