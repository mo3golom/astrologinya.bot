<?php

declare(strict_types=1);

namespace App\Service\Creatives\ObjectGetters;

use App\DTO\BaseCreativeObject;
use App\DTO\CreativeObjectInterface;
use App\Service\Creatives\CreativeFieldsContainer;
use App\Service\Creatives\Fields\FromModelFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Orchid\Attachment\Models\Attachment;

class FromModelObjectGetter implements CreativeObjectGetterInterface, CreativeFieldsContainer
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var string
     */
    private $titleKey;

    /**
     * @var string
     */
    private $textKey;

    /**
     * @var string
     */
    private $attachmentIdKey;

    public function setConfig(array $config): CreativeObjectGetterInterface
    {
        $this->model = app(trim($config['model_class']));
        $this->titleKey = trim($config['title_key'] ?? 'title');
        $this->textKey = trim($config['text_key'] ?? 'text');
        $this->attachmentIdKey = trim($config['attachment_id_key'] ?? 'attachment_id');

        return $this;
    }

    /**
     * @return CreativeObjectInterface
     * @throws \RuntimeException
     */
    public function getObject(): ?CreativeObjectInterface
    {
        $modelNamespace = get_class($this->model);
        $result = $this->model
            ->newQuery()
            ->select([sprintf('%s.*', $this->model->getTable())]) // Выбираем только поля модели
            ->leftJoin('creatives as c', function (JoinClause $join) use ($modelNamespace) {
                $join
                    ->on('object_name', '=', \DB::raw("'".$modelNamespace."'"))
                    ->on('object_id', '=', $this->model->getKeyName())
                ;
            })
            ->whereNull('c.creative_id') // Ищем для какой записи еще не сгенерировали "креатив"
            ->first()
        ;

        if (null === $result) {
            return null;
        }

        if (
            !isset($result->{$this->textKey}, $result->{$this->attachmentIdKey})
        ) {
            throw new \RuntimeException('Нет обязательных полей в выборке');
        }

        $attachmentId = $result->{$this->attachmentIdKey};
        $attachment = Attachment::find($attachmentId); // Это грязно, но пока лень думать как привести к типу Attachment по-другому

        if (null === $attachment) {
            throw new \RuntimeException(sprintf('Не найдено вложение с ID: %d', $attachmentId));
        }

        // Делаем немного тупое приведение типа, но хочу чтобы однозначно была либо строка либо null
        return
            (new BaseCreativeObject())
                ->setId($result->{$this->model->getKeyName()})
                ->setTitle(isset($result->{$this->titleKey}) ? (string) $result->{$this->titleKey} : null)
                ->setText(isset($result->{$this->textKey}) ? (string) $result->{$this->textKey} : null)
                ->setAttachment($attachment)
                ->setEntityName((string) $modelNamespace)
            ;
    }

    public function getFieldsClass(): string
    {
        return FromModelFields::class;
    }

}