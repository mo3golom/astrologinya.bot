<?php

declare(strict_types=1);

namespace App\Service\Creatives\ObjectGetters;

use App\DTO\BaseCreativeObject;
use App\DTO\CreativeObjectInterface;
use App\Models\CreativeModel;
use App\Service\Creatives\CreativeFieldsContainer;
use App\Service\Creatives\Fields\FromCreativeSettingFields;
use Orchid\Attachment\Models\Attachment;

class FromCreativeSettingObjectGetter implements CreativeObjectGetterInterface, CreativeFieldsContainer
{
    /**
     * @var array
     */
    private $keyValueTable;

    /**
     * @var array
     */
    private $attachments;

    /**
     * @var string
     */
    private $objectName;

    public function setConfig(array $config): CreativeObjectGetterInterface
    {
        $this->keyValueTable = (array) $config['creative_key_value_table'];
        $this->attachments = (array) $config['creative_attachments'];
        $this->objectName = (string) $config['object_name'];

        return $this;
    }

    public function getObject(): ?CreativeObjectInterface
    {
        $hashIds = [];
        foreach ($this->keyValueTable as $i => $data) {
            $hashIds[$data['key']] = $i;
        }

        $objectIds = CreativeModel::query()
            ->where('object_name', '=', $this->objectName)
            ->get()
            ->pluck('object_id')
            ->toArray()
        ;

        if (empty($objectIds)) {
            $data = array_shift($this->keyValueTable);
        } else {
            $diff = array_diff(array_column($this->keyValueTable,'key'), $objectIds);

            // если разница пустая, значит для всех уже сделали
            if (empty($diff)) {
                return null;
            }

            $key = array_shift($diff);
            $data = $this->keyValueTable[$hashIds[$key]];
        }

        if (!isset($data['key'], $data['value'])) {
            throw new \RuntimeException('Нет обязательных полей в выборке');
        }

        $attachmentId = $this->attachments[array_rand($this->attachments)];
        $attachment = Attachment::find($attachmentId); // Это грязно, но пока лень думать как привести к типу Attachment по-другому

        if (null === $attachment) {
            throw new \RuntimeException(sprintf('Не найдено вложение с ID: %d', $attachmentId));
        }

        return
            (new BaseCreativeObject())
                ->setId($data['key'])
                ->setTitle($data['key'])
                ->setText($data['value'])
                ->setAttachment($attachment)
                ->setEntityName($this->objectName)
            ;
    }

    public function getFieldsClass(): string
    {
        return FromCreativeSettingFields::class;
    }

}