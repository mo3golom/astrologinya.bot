<?php

use App\Service\Creatives\Generators\OnePageCurveTextAnimationVideoGenerator;
use App\Service\Creatives\Generators\OnePageTextVideoGenerator;
use App\Service\Creatives\ObjectGetters\FromCreativeSettingObjectGetter;
use App\Service\Creatives\ObjectGetters\FromModelObjectGetter;

return [
    'disk' => env('CREATIVE_DEFAULT_DISK', 'public'),
    'object_getters' => [
        FromModelObjectGetter::class => 'Геттер данных из модели',
        FromCreativeSettingObjectGetter::class => 'Геттер данных из настроек креатива',
    ],
    'generators' => [
        OnePageTextVideoGenerator::class => 'Генератора многострочного текста',
        OnePageCurveTextAnimationVideoGenerator::class => 'Генератора анимированного перечисления',
    ],
];