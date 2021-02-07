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
    private const TEXT = 'Облеченный властью человек портит Вам жизнь, но это не значит, что с этим нужно просто смириться. Для открытого конфликта пока не время, но собирать союзников уже можно.';

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