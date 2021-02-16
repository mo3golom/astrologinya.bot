<?php

namespace App\Service\TextImage;

interface TextImageServiceInterface
{
    public function setBoxWidth(int $boxWidth): TextImageServiceInterface;

    public function setBoxHeight(int $boxHeight): TextImageServiceInterface;

    public function setFontSize(int $fontSize): TextImageServiceInterface;

    public function setFontPath(string $fontPath): TextImageServiceInterface;

    public function setTextColor(string $textColor): TextImageServiceInterface;

    public function setFontHeight(int $fontHeight): TextImageServiceInterface;
}