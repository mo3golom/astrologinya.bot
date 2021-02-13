<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\TextEnumCurveImageService;
use App\Service\ZodiacTextImageService;

/**
 * Контроллер для отладки генерации изображения
 *
 * Class DebugImageController
 */
class DebugImageController
{
    public function index(TextEnumCurveImageService $service)
    {
        $image = $service
            ->setTitle('Плюсы Овна')
            ->setTextEnum(['Невинность', 'наивность', 'слепая вера', 'безрассудное мужество'])
            ->setEnumPrefix('• ')
            ->generateListImagesEnums()
            ->get()[2]
        ;

        return $image->response('png');
    }
}