<?php

namespace App\Service\Creatives\Generators;

use App\DTO\CreativeObjectInterface;

use Illuminate\Database\Eloquent\Model;

/**
 * Интерфейс генераторов
 *
 * Interface CreativeGeneratorInterface
 */
interface CreativeGeneratorInterface
{
    public function setConfig(array $config): CreativeGeneratorInterface;

    public function generate(CreativeObjectInterface $object): Model;
}