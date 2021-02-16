<?php

declare(strict_types=1);

namespace App\Service\TextImage;

use Carbon\Carbon;
use Intervention\Image\Gd\Font;
use Image as ImageFacade;
use Storage;

class TextEnumCurveImageService extends AbstractTextImageService
{
    /**
     * Тип кривой "линия"
     */
    public const CURVE_LINE = 'line';

    /**
     * тип кривой  "кривой зигзаг"
     */
    public const CURVE_ZIGZAG = 'zigzag';

    /**
     * @var string|null
     */
    private $title;

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

        $this->generate($curveType, -1);
        foreach ($this->textEnum as $i => $text) {
            $this->generate($curveType, $i);
        }

        return $this;
    }

    /**
     * @param string $disk
     * @param string|null $filename
     * @param string $format
     * @return array
     */
    public function save(string $disk = 'public', ?string $filename = null, string $format = 'png'): array
    {
        $result = [];
        $now = Carbon::now();

        foreach ($this->canvases as $i => $canvas) {
            $path = sprintf(
                '%s/%s/%s/%s.png',
                $now->year,
                $now->month,
                $now->day,
                ($filename ?? 'text_image_' . $now->format('dmYHis')) . $i
            );
            Storage::disk($disk)->put($path, $canvas->encode($format, 100));
            $result[] = $path;
        }

        return $result;
    }

    /**
     * @param string $curveType
     * @param null $iteration
     */
    private function generate(string $curveType = self::CURVE_LINE, $iteration = null): void
    {
        $startX = $this->textOffset;
        $startY = $this->textOffset + ($this->fontSize / 2);
        $yStep = 0 < $this->fontHeight ? $this->fontHeight : ($this->fontSize - ($this->fontSize / 7));
        $canvas = ImageFacade::canvas($this->boxWidth, $this->boxHeight);

        if (null !== $this->title) {
            $canvas->text($this->title, $startX, $startY, function (Font $font) {
                $font->file($this->fontPath);
                $font->size($this->fontSize);
                $font->color($this->textColor);
                $font->align('left');
                $font->valign('middle');
            });

            $startY += $yStep;
        }

        foreach ($this->textEnum as $i => $text) {
            if (
                null !== $iteration
                && $i > $iteration
            ) {
                break;
            }

            $yStepI = $i * $yStep;
            $xStep = $this->calculateXStep($curveType, (int) $yStep, $i);

            $canvas->text(
                $this->enumPrefix . mb_strtolower($text),
                (int) ($startX + $xStep),
                (int) ($startY + $yStepI),
                function (Font $font) {
                    $font->file($this->fontPath);
                    $font->size($this->fontSize / 1.5);
                    $font->color($this->textColor);
                    $font->align('left');
                    $font->valign('middle');
                }
            );
        }

        $this->canvases[] = $canvas;
    }

    /**
     * Высчитываем положение по X, основываясь на типе кривой
     *
     * @param string $curveType
     * @param int $yStep
     * @param int $iteration
     * @return float|int
     */
    private function calculateXStep(string $curveType, int $yStep, int $iteration)
    {
        switch ($curveType) {
            case self::CURVE_ZIGZAG:
                $xStep = 0 === ($iteration % 2) ? 0 : $yStep * 4;

                if (0 > $xStep) {
                    $xStep = 0;
                }

                break;
            case self::CURVE_LINE:
            default:
                $xStep = $yStep * $iteration;

                break;
        }

        return $xStep;
    }
}