<?php

declare(strict_types=1);

namespace App\Service\Creatives\Managers;

use App\Service\Creatives\Generators\CreativeGeneratorInterface;
use App\Service\Creatives\ObjectGetters\CreativeObjectGetterInterface;

interface CreativeManagerInterface
{
    public function exec(): void;

    public function setObjectGetter(CreativeObjectGetterInterface $objectGetter): CreativeManagerInterface;

    public function setGenerator(CreativeGeneratorInterface $generator): CreativeManagerInterface;

    public function setCreativeSettingId(int $settingId): CreativeManagerInterface;
}