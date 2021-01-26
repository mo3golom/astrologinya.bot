<?php

namespace App\Http\Controllers;

use App\Models\HoroscopeModel;
use App\Repository\HoroscopeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainApiController extends Controller
{
    public function getZodiacVideoUrls(HoroscopeRepository $horoscopeRepository): JsonResponse
    {
        $horoscopes = $horoscopeRepository->getAllWithVideo();

        return response()->json(
            $horoscopes
                ->map(static function (HoroscopeModel $horoscopeModel) {
                    return $horoscopeModel->attachment->url();
                })
                ->toArray()
        );
    }
}
