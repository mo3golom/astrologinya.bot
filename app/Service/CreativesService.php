<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\CreativeRepository;
use App\Repository\CreativeSettingsRepository;
use App\Repository\Orchid\AttachmentRepository;

class CreativesService
{
    /**
     * @var CreativeRepository
     */
    private $creativeRepository;

    /**
     * @var CreativeSettingsRepository
     */
    private $creativeSettingsRepository;

    /**
     * @var AttachmentRepository
     */
    private $attachmentRepository;

    /**
     * CreativesService constructor.
     *
     * @param CreativeRepository $creativeRepository
     * @param CreativeSettingsRepository $creativeSettingsRepository
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        CreativeRepository $creativeRepository,
        CreativeSettingsRepository $creativeSettingsRepository,
        AttachmentRepository $attachmentRepository
    ) {
        $this->creativeRepository = $creativeRepository;
        $this->creativeSettingsRepository = $creativeSettingsRepository;
        $this->attachmentRepository = $attachmentRepository;
    }

    /**
     * @param string $objectName
     * @param array $objectIds
     */
    public function deleteByObjectNameAndObjectIds(string $objectName, array $objectIds): void
    {
        $creatives = $this->creativeRepository->getByObjectNameAndObjectIds($objectName, $objectIds);

        // Удаляем вложения
        $attachmentIds = $creatives->pluck('attachment_id')->toArray();
        $attachments = $this->attachmentRepository->getByIds($attachmentIds);

        foreach ($attachments as $attachment) {
            $attachment->delete();
        }

        // Убираем признак "Завершен" у настроек
        $creativeSettingIds = array_unique($creatives->pluck('creative_setting_id')->toArray());
        $this->creativeSettingsRepository->setNotCompleteByIds($creativeSettingIds);

        // Удаляем креативы
        $this->creativeRepository->deleteByIds($creatives->pluck('creative_id')->toArray());
    }
}