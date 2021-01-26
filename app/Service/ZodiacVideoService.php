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

    private const BITRATE = 8580;

    /**
     * @var int
     */
    private $positionX;

    /**
     * @var int
     */
    private $positionY;

    private $zodiacTextImageService;

    public function __construct(ZodiacTextImageService $zodiacTextImageService)
    {
        $config = config('zodiac.image_text');
        $this->positionX = $config['position_x'];
        $this->positionY = $config['position_y'];

        $this->zodiacTextImageService = $zodiacTextImageService;
    }

    /**
     * @param string $templateVideoPath
     * @param string $text
     * @param string $fileName
     * @return Attachment
     */
    public function generate(string $templateVideoPath, string $text, string $fileName): Attachment
    {
        $image = $this->zodiacTextImageService->generate($text, self::SAVE_PATH);
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($templateVideoPath);
        $video
            ->filters()
            ->watermark($image, [
                'position' => 'absolute',
                'x' => $this->positionX,
                'y' => $this->positionY,
            ])
        ;

        $mp4Format = new X264();
        // Fix for error "Encoding failed : Can't save to X264"
        // See: https://github.com/PHP-FFMpeg/PHP-FFMpeg/issues/310
        $mp4Format->setAudioCodec("libmp3lame");
        $mp4Format->setKiloBitrate(self::BITRATE);

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