<?php

declare(strict_types=1);

namespace App\Service;

use Intervention\Image\Gd\Font;
use Illuminate\Support\Facades\Storage;
use Image;

class ZodiacTextImageService
{
    /**
     * Cпецсимволы с нестандартной высотой, для них необходимо корректировка высота линии текста
     */
    private const SPECIAL_CHARS = ['ё', 'й', 'ф', 'б'];

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

    public function __construct()
    {
        $config = config('zodiac.image_text');
        $this->offset = (int) $config['offset'];
        $this->width = (int) $config['width'];
        $this->height = (int) $config['height'];
        $this->maxLen = (int) $config['max_len'];
        $this->fontSize = (int) $config['font_size'];
    }

    public function generate(string $text, string $disk = 'public'): string
    {
        $lines = explode("\n", wordwrap($this->transformText($text), $this->maxLen));
        $y = $this->offset;
        $canvas = Image::canvas($this->width, $this->height);

        foreach ($lines as $line) {
            $corrector = 0;
            if ($this->checkSpecialChars($line)) {
                $corrector = 7;
            }
            $canvas->text($line, $this->width / 2, $y - $corrector, function (Font $font) {
                $font->file(sprintf('%s/public/fonts/kurale.ttf', base_path()));
                $font->size($this->fontSize);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('top');
            });

            $y += $this->fontSize;
        }

        $path = sprintf('horoscope_picture/horoscope_image_%s.png', date('dmyH'));
        Storage::disk($disk)->put($path, $canvas->encode('png', 100));

        return $path;
    }

    /**
     * @param string $text
     * @return string
     */
    private function transformText(string $text): string
    {
        return  mb_strtoupper(mb_substr($text, 0, 1)) . mb_strtolower(mb_substr($text, 1));
    }

    /**
     * @param string $text
     * @return bool
     */
    private function checkSpecialChars(string $text): bool
    {
        foreach (self::SPECIAL_CHARS as $char) {
            if (false === mb_stripos($text, $char)) {
                continue;
            }

            return true;
        }

        return false;
    }
}