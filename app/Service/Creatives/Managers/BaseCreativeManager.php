<?php

declare(strict_types=1);

namespace App\Service\Creatives\Managers;

use App\Repository\CreativeRepository;
use App\Repository\CreativeSettingsRepository;
use App\Service\Creatives\Generators\CreativeGeneratorInterface;
use App\Service\Creatives\ObjectGetters\CreativeObjectGetterInterface;
use Carbon\Carbon;

class BaseCreativeManager implements CreativeManagerInterface
{
    /**
     * @var
     */
    private $objectGetter;

    /**
     * @var CreativeGeneratorInterface
     */
    private $generator;

    /**
     * @var CreativeRepository
     */
    private $creativeRepository;

    /**
     * @var int
     */
    private $creativeSettingId;

    /**
     * @var CreativeSettingsRepository
     */
    private $creativeSettingRepository;

    public function __construct(CreativeRepository $creativeRepository, CreativeSettingsRepository $creativeSettingsRepository)
    {
        $this->creativeRepository = $creativeRepository;
        $this->creativeSettingRepository = $creativeSettingsRepository;
    }

    public function exec(): void
    {
        try {
            $object = $this->objectGetter->getObject();

            // Если ничего не найдено, то считаем, что уже для всех есть сгенерированный креатив
            if (null === $object) {
                $this->creativeSettingRepository->updateById(
                    $this->creativeSettingId,
                    ['is_complete' => true]
                );

                return;
            }

            $attachment = $this->generator->generate($object);

            $this->creativeRepository->create([
                'creative_setting_id' => $this->creativeSettingId,
                'object_name' => $object->getEntityName(),
                'object_id' => $object->getId(),
                'attachment_id' => $attachment->id,
            ]);

            // Обновляем дату последней генерации у настройки
            $this->creativeSettingRepository->updateById(
                $this->creativeSettingId,
                ['last_generated_at' => Carbon::now()]
            );
        } catch (\Throwable $th) {
            \Log::error($th->getMessage());
        }
    }

    public function setObjectGetter(CreativeObjectGetterInterface $objectGetter): CreativeManagerInterface
    {
        $this->objectGetter = $objectGetter;

        return $this;
    }

    public function setGenerator(CreativeGeneratorInterface $generator): CreativeManagerInterface
    {
        $this->generator = $generator;

        return $this;
    }

    public function setCreativeSettingId(int $settingId): CreativeManagerInterface
    {
        $this->creativeSettingId = $settingId;

        return $this;
    }
}