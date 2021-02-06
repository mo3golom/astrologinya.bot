<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\ZodiacTextImageService;

/**
 * Контроллер для отладки генерации изображения
 *
 * Class DebugImageController
 */
class DebugImageController
{
    private const TEXT = 'Ваш независимый ум очень быстро генерирует гениальные идеи, но делиться ими пока что рано. Лучше потратьте силы на мозговые штурмы, а лучшие планы запишите.';

    /**
     * @param ZodiacTextImageService $service
     * @return mixed
     */
    public function index(ZodiacTextImageService $service)
    {
        $image = $service->generate(self::TEXT);

        return $image->response('png');
    }
}