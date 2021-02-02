<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\Orchid\AttachmentRepository;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;

class ZodiacVideoService
{
    /**
     * Битрейт видео
     */
    private const BITRATE = 8580;

    /**
     * @var int
     */
    private $positionX;

    /**
     * @var int
     */
    private $positionY;

    /**
     * @var ZodiacTextImageService
     */
    private $zodiacTextImageService;

    private $attachmentRepository;

    /**
     * ZodiacVideoService constructor.
     *
     * @param ZodiacTextImageService $zodiacTextImageService
     */
    public function __construct(ZodiacTextImageService $zodiacTextImageService, AttachmentRepository $attachmentRepository)
    {
        $config = config('zodiac.image_text');
        $this->positionX = $config['position_x'];
        $this->positionY = $config['position_y'];

        $this->zodiacTextImageService = $zodiacTextImageService;
        $this->attachmentRepository = $attachmentRepository;
    }

    /**
     * @param string $templateVideoUrl
     * @param string $text
     * @param string $fileName
     * @param string $disk
     * @return Model
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function generate(string $templateVideoUrl, string $text, string $fileName, string $disk = 'public'): Model
    {
        $storage = Storage::disk($disk);
        $fileName = sprintf('horoscope_video/%s%s.mp4', $fileName, time());

        $image = $this->zodiacTextImageService->generate($text, $disk);

        $mp4Format = new X264();
        // Fix for error "Encoding failed : Can't save to X264"
        // See: https://github.com/PHP-FFMpeg/PHP-FFMpeg/issues/310
        $mp4Format->setAudioCodec("libmp3lame");
        $mp4Format->setKiloBitrate(self::BITRATE);

        try {
            FFMpeg::openUrl($templateVideoUrl, [])
                ->addWatermark(function (WatermarkFactory $watermark) use ($image, $disk) {
                    $watermark->fromDisk($disk)
                        ->open($image)
                        ->left($this->positionX)
                        ->top($this->positionY)
                    ;
                })
                ->export()
                ->toDisk($disk)
                ->inFormat($mp4Format)
                ->save($fileName)
            ;
        } catch (EncodingException $exception) {
            \Log::error('ffmpeg_error: '.$exception->getCommand() . $exception->getErrorOutput());
        }

        $storage->delete($image);

        return $this->attachmentRepository->createFromDiskAndPath($disk, $fileName);
    }
}