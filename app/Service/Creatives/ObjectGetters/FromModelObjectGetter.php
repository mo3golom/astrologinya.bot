<?php

declare(strict_types=1);

namespace App\Service\Creatives\ObjectGetters;

use App\DTO\BaseCreativeObject;
use App\DTO\CreativeObjectInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

class FromModelObjectGetter implements CreativeObjectGetterInterface
{
    private $model;

    private $titleKey;

    private $textKey;

    public function __construct(Model $model, $titleKey = 'title', $textKey = 'text')
    {
        $this->model = $model;
        $this->titleKey = $titleKey;
        $this->textKey = $textKey;
    }

    public function getObject(): ?CreativeObjectInterface
    {
        $modelNamespace = get_class($this->model);
        $result = $this->model
            ->newQuery()
            ->select([sprintf('%s.*', $this->model->getTable())]) // Выбираем только поля модели
            ->leftJoin('creatives as c', function (JoinClause $join) use ($modelNamespace) {
                $join
                    ->on('object_name', '=', $modelNamespace)
                    ->on('object_id', '=', $this->model->getKeyName())
                ;
            })
            ->whereNull('c.creative_id') // Ищем для какой записи еще не сгенерировали "креатив"
            ->first()
        ;

        if (null === $result) {
            return null;
        }

        // Делаем немного тупое приведение типа, но хочу чтобы однозначно была либо строка либо null
        return
            (new BaseCreativeObject())
                ->setTitle(isset($result->{$this->titleKey}) ? (string) $result->{$this->titleKey} : null)
                ->setText(isset($result->{$this->textKey}) ? (string) $result->{$this->textKey} : null)
                ->setEntityName((string) $modelNamespace)
            ;
    }
}