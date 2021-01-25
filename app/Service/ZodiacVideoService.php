<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\HoroscopeModel;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Gd\Font;
use Orchid\Attachment\File;
use Orchid\Attachment\Models\Attachment;

class ZodiacVideoService
{
    private const SAVE_PATH = 'public/storage/horoscope_video';

    /**
     * @param string $templateVideoPath
     * @param string $text
     * @param string $fileName
     * @return Attachment
     */
    public function generate(string $templateVideoPath, string $text, string $fileName): Attachment
    {
        $image = $this->generateTextImage($text);
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($templateVideoPath);
        $video
            ->filters()
            ->watermark($image, [
                'position' => 'absolute',
                'x' => 65,
                'y' => 1037,
            ])
        ;

        $mp4Format = new X264();
        // Fix for error "Encoding failed : Can't save to X264"
        // See: https://github.com/PHP-FFMpeg/PHP-FFMpeg/issues/310
        $mp4Format->setAudioCodec("libmp3lame");
        $mp4Format->setKiloBitrate(8580);

        // Создаем папку в паблике, если ее еще нет
        $this->createDirIfNotExists(self::SAVE_PATH);

        // Сохраняем видео
        $path = sprintf('%s/%s.mp4', self::SAVE_PATH, $fileName);
        $video->save($mp4Format, $path);
        $attachment = (new File($this->transformPathToUploadedFile($path)))->load();
        unlink($path);
        unlink($image);

        return $attachment;
    }

    /**
     * @param string $text
     * @return string
     */
    private function generateTextImage(string $text): string
    {
        $offset = 10;
        $width = 950;
        $height = 640;
        $max_len = 60;
        $font_size = 60;
        $font_height = 30;
        $lines = explode("\n", wordwrap($text, $max_len));
        $y = $offset;
        $canvas = Image::canvas($width, $height);

        foreach ($lines as $line) {
            $canvas->text($line, $width / 2, $y, static function (Font $font) use ($font_size) {
                $font
                    ->file(sprintf('%s/public/fonts/kurale.ttf', base_path()))
                    ->size($font_size)
                    ->color('#ffffff')
                    ->align('center')
                    ->valign('top')
                ;
            });

            $y += $font_height * 2;
        }

        $path = sprintf('%s/horoscope_image_%s.png', self::SAVE_PATH, date('dmyH'));
        $canvas->save($path);

        return $path;
    }

    /**
     * @param string $dirPath
     */
    private function createDirIfNotExists(string $dirPath): void
    {
        if (
            !file_exists($dirPath)
            && !mkdir($dirPath, 0777, true)
            && !is_dir($dirPath)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirPath));
        }
    }

    /**
     * @param string $path
     * @return UploadedFile
     */
    private function transformPathToUploadedFile(string $path): UploadedFile
    {
        $pathInfo = pathinfo($path);

        return new UploadedFile(
            $path,
            $pathInfo['basename'],
            $pathInfo['extension'],
        );
    }
}