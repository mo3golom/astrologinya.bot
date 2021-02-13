<?php

namespace App\Service\Creatives\ObjectGetters;

use App\DTO\CreativeObjectInterface;

/**
 * Интерфейс геттеров объектов
 *
 * Interface CreativeObjectGetterInterface
 */
interface CreativeObjectGetterInterface
{
    public function setConfig(array $config): CreativeObjectGetterInterface;

    /**
     * Возвращаем null в случае если ничего не нашли
     * @return CreativeObjectInterface|null
     */
    public function getObject(): ?CreativeObjectInterface;
}