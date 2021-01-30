<?php

namespace App\Http\Controllers;

use App\Repository\HoroscopeRepository;
use Illuminate\Http\JsonResponse;

class MainApiController extends Controller
{
    public function getZodiacVideoUrls(HoroscopeRepository $horoscopeRepository): JsonResponse
    {
        $horoscopes = $horoscopeRepository->getAllWithVideo();

        return response()->json(
            $horoscopes
                ->pluck('video_url')
                ->toArray()
        );
    }
}
