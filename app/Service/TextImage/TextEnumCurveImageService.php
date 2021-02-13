<?php

declare(strict_types=1);

namespace App\Service\TextImage;

use Intervention\Image\Gd\Font;
use Image as ImageFacade;

class TextEnumCurveImageService extends AbstractTextImageService
{
    public const CURVE_LINE = 'line';

    /**
     * @var string|null
     */
    private $title = null;

    /**
     * @var array
     */
    private $textEnum = [];

    /**
     * @var string
     */
    private $enumPrefix = '';

    /**
     * @var array
     */
    private $canvases = [];

    /**
     * @param string $title
     * @return TextEnumCurveImageService
     */
    public function setTitle(?string $title): TextEnumCurveImageService
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param array $textEnum
     * @return TextEnumCurveImageService
     */
    public function setTextEnum(array $textEnum): TextEnumCurveImageService
    {
        usort($textEnum, static function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        $this->textEnum = $textEnum;

        return $this;
    }

    /**
     * @param string $enumPrefix
     * @return TextEnumCurveImageService
     */
    public function setEnumPrefix(string $enumPrefix): TextEnumCurveImageService
    {
        $this->enumPrefix = $enumPrefix;

        return $this;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->canvases;
    }

    public function generateOneImageEnums(string $curveType = self::CURVE_LINE): TextEnumCurveImageService
    {
        $this->canvases = [];
        $this->generate($curveType);

        return $this;
    }

    /**
     * @param string $curveType
     * @return $this
     */
    public function generateListImagesEnums(string $curveType = self::CURVE_LINE): TextEnumCurveImageService
    {
        $this->canvases = [];
        foreach ($this->textEnum as $i => $text) {
            $this->generate($curveType, $i);
        }

        return $this;
    }

    /**
     * @param string $curveType
     * @param null $iteration
     */
    private function generate(string $curveType = self::CURVE_LINE, $iteration = null): void
    {
        $offsetX = 32;
        $startY = ($this->fontSize / 2);
        $canvas = ImageFacade::canvas($this->boxWidth, $this->boxHeight);

        if (null !== $this->title) {
            $canvas->text($this->title, $offsetX, $startY, function (Font $font) {
                $font->file($this->fontPath);
                $font->size($this->fontSize);
                $font->color($this->textColor);
                $font->align('left');
                $font->valign('middle');
            });
        }

        foreach ($this->textEnum as $i => $text) {
            $canvas->text(
                $this->enumPrefix . mb_strtolower($text),
                $offsetX + $i * ($this->fontSize - ($this->fontSize / 7)),
                $startY + ($i + 1) * ($this->fontSize - ($this->fontSize / 7)),
                function (Font $font) {
                    $font->file($this->fontPath);
                    $font->size($this->fontSize / 1.5);
                    $font->color($this->textColor);
                    $font->align('left');
                    $font->valign('middle');
                }
            );

            if (
                null !== $iteration
                && $i >= $iteration
            ) {
                break;
            }
        }

        $this->canvases[] = $canvas;
    }
}