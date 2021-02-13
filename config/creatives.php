<?php

return [
    'disk' => env('CREATIVE_DEFAULT_DISK', 'public'),
    'creatives' => [
        'horoscopes' => [
            'name' => 'Гороскопы',
            'object_getter' => \App\Service\Creatives\ObjectGetters\FromModelObjectGetter::class,
            'generator' => \App\Service\Creatives\Generators\OnePageTextVideoGenerator::class,
        ],
    ],
];