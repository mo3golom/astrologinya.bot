<?php

declare(strict_types=1);

namespace App\Service;

use Carbon\Carbon;
use Intervention\Image\Gd\Font;
use Illuminate\Support\Facades\Storage;
use Image;

class ZodiacTextImageService
{
    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $maxLen;

    /**
     * @var int
     */
    private $fontSize;

    /**
     * @var bool
     */
    private $enableDate;

    /**
     * ZodiacTextImageService constructor.
     */
    public function __construct()
    {
        $config = config('zodiac.image_text');
        $this->offset = (int) $config['offset'];
        $this->width = (int) $config['width'];
        $this->height = (int) $config['height'];
        $this->maxLen = (int) $config['max_len'];
        $this->fontSize = (int) $config['font_size'];
        $this->enableDate = (bool) $config['enable_date'];
    }

    /**
     * @param string $text
     * @param string $disk
     * @return string
     */
    public function generateWithSave(string $text, string $disk = 'public'): string
    {
        $canvas = $this->generate($text);

        return $this->save($canvas, $disk);
    }

    /**
     * @param string $text
     * @return \Intervention\Image\Image
     */
    public function generate(string $text): \Intervention\Image\Image
    {
        $lines = explode("\n", wordwrap($text, $this->maxLen));
        $y = $this->offset + ($this->fontSize / 2);
        $canvas = Image::canvas($this->width, $this->height);

        foreach ($lines as $i => $line) {
            $this->addText($canvas, $line, $this->width / 2, (int) $y);

            $y += $this->fontSize - ($this->fontSize / 7);
        }

        if ($this->enableDate) {
            $this->addText($canvas, Carbon::now()->format('d.m.Y'), $this->width / 2, (int) $y +  ($this->fontSize / 3));
        }

        return $canvas;
    }

    /**
     * @param \Intervention\Image\Image $canvas
     * @param string $text
     * @param int $x
     * @param int $y
     */
    private function addText(\Intervention\Image\Image $canvas, string $text, int $x, int $y): void
    {
        $canvas->text($text, $x, $y, function (Font $font) {
            $font->file(sprintf('%s/public/fonts/kurale.ttf', base_path()));
            $font->size($this->fontSize);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
    }

    /**
     * @param \Intervention\Image\Image $image
     * @param string $disk
     * @return string
     */
    public function save(\Intervention\Image\Image $image, string $disk = 'public'): string
    {
        $path = sprintf('horoscope_picture/horoscope_image_%s.png', date('dmyH'));
        Storage::disk($disk)->put($path, $image->encode('png', 100));

        return $path;
    }
}