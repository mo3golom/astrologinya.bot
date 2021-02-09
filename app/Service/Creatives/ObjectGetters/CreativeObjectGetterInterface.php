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
    public function getObject(): ?CreativeObjectInterface;
}