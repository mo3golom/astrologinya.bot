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

    /**
     * @var string
     */
    private $creativeTypes;

    public function __construct(CreativeSettingsRepository $creativeSettingsRepository)
    {
        $this->creativeSettingsRepository = $creativeSettingsRepository;
        $this->creativeTypes = config('creatives.creatives');
    }

    public function getManager(int $creativeSettingId): CreativeManagerInterface
    {
        /** @var CreativeSettingModel $creativeSetting */
        $creativeSetting = $this->creativeSettingsRepository->find($creativeSettingId);

        if (!isset($this->creativeTypes[$creativeSetting->type])) {
            throw new \RuntimeException(sprintf('Типа креатива %s нет в конфигурации', $creativeSetting->type));
        }

        $creative = $this->creativeTypes[$creativeSetting->type];

        /** @var CreativeObjectGetterInterface $objectGetter */
        $objectGetter = app($creative['object_getter']);
        $objectGetter->setConfig($creativeSetting->settings);

        /** @var CreativeGeneratorInterface $generator */
        $generator = app($creative['generator']);
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