<?php

declare(strict_types=1);

namespace App\Service;

use Intervention\Image\Facades\Image;
use Intervention\Image\Gd\Font;

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
        $this->offset = $config['offset'];
        $this->width = $config['width'];
        $this->height = $config['height'];
        $this->maxLen = $config['max_len'];
        $this->fontSize = $config['font_size'];
        $this->fontHeight = $config['font_height'];
    }

    public function generate(string $text, string $savePath): string
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

        $path = sprintf('%s/horoscope_image_%s.png', $savePath, date('dmyH'));
        $canvas->save($path);

        return $path;
    }
}