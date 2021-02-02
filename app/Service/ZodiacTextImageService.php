<?php

declare(strict_types=1);

namespace App\Service;

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
     * @var int
     */
    private $fontHeight;

    public function __construct()
    {
        $config = config('zodiac.image_text');
        $this->offset = (int) $config['offset'];
        $this->width = (int) $config['width'];
        $this->height = (int) $config['height'];
        $this->maxLen = (int) $config['max_len'];
        $this->fontSize = (int) $config['font_size'];
        $this->fontHeight = (int) $config['font_height'];
    }

    public function generate(string $text, string $disk = 'public'): string
    {
        $lines = explode("\n", wordwrap($text, $this->maxLen));
        $y = $this->offset;
        $canvas = Image::canvas($this->width, $this->height);

        foreach ($lines as $line) {
            $canvas->text($line, $this->width / 2, $y, function (Font $font) {
                $font
                    ->file(sprintf('%s/public/fonts/kurale.ttf', base_path()))
                    ->size($this->fontSize)
                    ->color('#ffffff')
                    ->align('center')
                    ->valign('top')
                ;
            });

            $y += $this->fontHeight * 2;
        }

        $path = sprintf('horoscope_picture/horoscope_image_%s.png', date('dmyH'));
        Storage::disk($disk)->put($path, $canvas->encode('png', 100));

        return $path;
    }
}