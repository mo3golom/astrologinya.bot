<?php

declare(strict_types=1);

namespace App\Service;

use Carbon\Carbon;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Image as ImageFacade;
use Storage;

class TextImageService
{
    /**
     * @var int
     */
    private $textOffset = 0;

    /**
     * @var int
     */
    private $boxWidth = 1080;

    /**
     * @var int
     */
    private $boxHeight = 640;

    /**
     * @var int
     */
    private $lineMaxLength = 45;

    /**
     * @var int
     */
    private $fontSize = 60;

    /**
     * @var array
     */
    private $lines = [];

    /**
     * @var string
     */
    private $fontPath;

    /**
     * @var string
     */
    private $textColor;

    /**
     * @var Image|null
     */
    private $canvas = null;

    /**
     * @param int $textOffset
     * @return TextImageService
     */
    public function setTextOffset(int $textOffset): TextImageService
    {
        $this->textOffset = $textOffset;

        return $this;
    }

    /**
     * @param int $boxWidth
     * @return TextImageService
     */
    public function setBoxWidth(int $boxWidth): TextImageService
    {
        $this->boxWidth = $boxWidth;

        return $this;
    }

    /**
     * @param int $boxHeight
     * @return TextImageService
     */
    public function setBoxHeight(int $boxHeight): TextImageService
    {
        $this->boxHeight = $boxHeight;

        return $this;
    }

    /**
     * @param int $lineMaxLength
     * @return TextImageService
     */
    public function setLineMaxLength(int $lineMaxLength): TextImageService
    {
        $this->lineMaxLength = $lineMaxLength;

        return $this;
    }

    /**
     * @param int $fontSize
     * @return TextImageService
     */
    public function setFontSize(int $fontSize): TextImageService
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * @param string $fontPath
     * @return TextImageService
     */
    public function setFontPath(string $fontPath): TextImageService
    {
        $this->fontPath = $fontPath;

        return $this;
    }

    /**
     * @param string $textColor
     * @return TextImageService
     */
    public function setTextColor(string $textColor): TextImageService
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function setText(string $text): TextImageService
    {
        $this->lines = explode("\n", wordwrap($text, $this->lineMaxLength));

        return $this;
    }

    public function addLine(string $text): TextImageService
    {
        $this->lines[] = $text;

        return $this;
    }

    public function generate(): TextImageService
    {
        $y = $this->textOffset + ($this->fontSize / 2);
        $this->canvas = ImageFacade::canvas($this->boxWidth, $this->boxHeight);

        foreach ($this->lines as $i => $line) {
            $this->canvas->text($line, $this->boxWidth / 2, $y, function (Font $font) {
                $font->file($this->fontPath);
                $font->size($this->fontSize);
                $font->color($this->textColor);
                $font->align('center');
                $font->valign('middle');
            });

            $y += $this->fontSize - ($this->fontSize / 7);
        }

        return $this;
    }

    /**
     * @return Image|null
     */
    public function get(): ?Image
    {
        return $this->canvas;
    }

    /**
     * @param string $disk
     * @param string|null $filename
     * @param string $format
     * @return string
     */
    public function save(string $disk = 'public', ?string $filename = null, string $format = 'png'): string
    {
        $now = Carbon::now();
        $path = sprintf(
            '%s/%s/%s/%s.png',
            $now->year,
            $now->month,
            $now->day,
            $filename ?? 'text_image_' . $now->format('dmYHis')
        );

        Storage::disk($disk)->put($path, $this->canvas->encode($format, 100));

        return $path;
    }
}