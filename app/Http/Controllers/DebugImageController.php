<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\TextImage\TextEnumCurveImageService;

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
            ->setTextOffset(20)
            ->setFontHeight(70)
            ->setTextEnum(['Невинность', 'наивность', 'слепая вера', 'безрассудное мужество', 'Невинность', 'наивность', 'слепая вера'])
            ->setEnumPrefix('• ')
            ->setFontPath(sprintf('%s/public/fonts/kurale.ttf', base_path()))
            ->generateOneImageEnums(TextEnumCurveImageService::CURVE_LINE)
            ->get()[0]
        ;

        return $image->response('png');
    }
}