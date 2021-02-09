<?php

namespace App\Service\Creatives\Generators;

use App\DTO\CreativeObjectInterface;
use Orchid\Attachment\Models\Attachment;

/**
 * Интерфейс генераторов
 *
 * Interface CreativeGeneratorInterface
 */
interface CreativeGeneratorInterface
{
    public function generate(Attachment $attachment, CreativeObjectInterface $object);
}