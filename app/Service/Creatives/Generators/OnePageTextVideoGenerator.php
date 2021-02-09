<?php

declare(strict_types=1);

namespace App\Service\Creatives\Generators;

use App\DTO\CreativeObjectInterface;
use App\Repository\Orchid\AttachmentRepository;
use App\Service\TextImageService;
use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\FileNotFoundException;
use Orchid\Attachment\Models\Attachment;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;
use Storage;

class OnePageTextVideoGenerator implements CreativeGeneratorInterface
{
    /**
     * @var string
     */
    private $disk;

    /**
     * @var TextImageService
     */
    private $textImageService;

    /**
     * @var array
     */
    private $config;
    /**
     *
     * @var AttachmentRepository
     */
    private $attachmentRepository;

    public function __construct(array $config, TextImageService $textImageService, AttachmentRepository $attachmentRepository)
    {
        $this->textImageService = $textImageService;
        $this->config = $config;
        $this->attachmentRepository = $attachmentRepository;

        $this->disk = config('creatives.disk');
    }

    /**
     * @param Attachment $attachment
     * @param CreativeObjectInterface $object
     * @return Model|null
     */
    public function generate(Attachment $attachment, CreativeObjectInterface $object): ?Model
    {
        $storage = Storage::disk($this->disk);

        // Если нет текста для размещения в видео, то прерываем генерацию
        if (null === $object->getText()) {
            return null;
        }

        $now = Carbon::now();
        $fileName = sprintf(
            '%s/%s/%s/%s.png',
            $now->year,
            $now->month,
            $now->day,
            'creative_from_model_video_' . $now->format('dmYHis')
        );

        $image = $this->textImageService
            ->setBoxWidth($this->config['box_width'] ?? 1080)
            ->setBoxHeight($this->config['box_height'] ?? 640)
            ->setFontPath(sprintf('%s/public/fonts/kurale.ttf', base_path()))
            ->setFontSize($this->config['font_size'] ?? 25)
            ->setTextColor($this->config['text_color'] ?? '#ffffff')
            ->setLineMaxLength($this->config['line_max_length'])
            ->setTextOffset($this->config['text_offset'] ?? 0)
            ->setText($object->getText())
            ->generate()
            ->save($this->disk)
        ;

        $mp4Format = new X264();
        $mp4Format->setAudioCodec("libmp3lame");
        $mp4Format->setKiloBitrate(8580);

        try {
            FFMpeg::fromDisk($attachment->disk)
                ->open($attachment->physicalPath())
                ->addWatermark(function (WatermarkFactory $watermark) use ($image) {
                    $watermark->fromDisk($this->disk)
                        ->open($image)
                        ->left($this->config['position_x'])
                        ->top($this->config['position_y'])
                    ;
                })
                ->export()
                ->toDisk($this->disk)
                ->inFormat($mp4Format)
                ->save($fileName)
            ;
        } catch (EncodingException $exception) {
            \Log::error('ffmpeg_error: ' . $exception->getCommand() . $exception->getErrorOutput());
        }

        $storage->delete($image);

        try {
            $video = $this->attachmentRepository->createFromDiskAndPath($this->disk, $fileName);
        } catch (FileNotFoundException $e) {
            \Log::error('save video error: ' . $e->getMessage());

            return null;
        }

        return $video;
    }

}