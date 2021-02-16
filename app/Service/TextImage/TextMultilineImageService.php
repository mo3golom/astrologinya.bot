<?php

declare(strict_types=1);

namespace App\Service\TextImage;

use Carbon\Carbon;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Image as ImageFacade;
use Storage;

class TextMultilineImageService extends AbstractTextImageService
{
    /**
     * @var int
     */
    private $lineMaxLength = 45;

    /**
     * @var array
     */
    private $lines = [];

    /**
     * @var Image|null
     */
    private $canvas;

    /**
     * @param int $lineMaxLength
     * @return TextMultilineImageService
     */
    public function setLineMaxLength(int $lineMaxLength): TextMultilineImageService
    {
        $this->lineMaxLength = $lineMaxLength;

        return $this;
    }

    public function setText(string $text): TextMultilineImageService
    {
        $this->lines = explode("\n", wordwrap($text, $this->lineMaxLength));

        return $this;
    }

    public function addLine(string $text): TextMultilineImageService
    {
        $this->lines[] = $text;

        return $this;
    }

    public function generate(): TextMultilineImageService
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

            $y += 0 < $this->fontHeight ? $this->fontHeight : ($this->fontSize - ($this->fontSize / 7));
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