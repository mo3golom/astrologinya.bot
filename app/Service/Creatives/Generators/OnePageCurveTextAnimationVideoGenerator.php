<?php

declare(strict_types=1);

namespace App\Service\Creatives\Generators;

use App\DTO\CreativeObjectInterface;
use App\Repository\Orchid\AttachmentRepository;
use App\Service\Creatives\CreativeFieldsContainer;
use App\Service\Creatives\Fields\OnePageCurveTextAnimationVideoFields;
use App\Service\TextImage\TextEnumCurveImageService;
use Carbon\Carbon;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\FileNotFoundException;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;
use FFMpeg;
use Storage;

class OnePageCurveTextAnimationVideoGenerator extends AbstractTextVideoGenerator implements CreativeFieldsContainer
{
    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $frameDuration;

    /**
     * @var TextEnumCurveImageService
     */
    private $textEnumCurveImageService;

    /**
     * @var mixed|string
     */
    private $textPrefix;

    /**
     * @var AttachmentRepository
     */
    private $attachmentRepository;

    /**
     * OnePageCurveTextAnimationVideoGenerator constructor.
     *
     * @param TextEnumCurveImageService $textEnumCurveImageService
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(TextEnumCurveImageService $textEnumCurveImageService, AttachmentRepository $attachmentRepository)
    {
        $this->textEnumCurveImageService = $textEnumCurveImageService;
        $this->attachmentRepository = $attachmentRepository;

        parent::__construct();
    }

    /**
     * @param array $config
     * @return CreativeGeneratorInterface
     */
    public function setConfig(array $config): CreativeGeneratorInterface
    {
        $this->duration = (int) ($this->config['duration'] ?? 15);
        $this->frameDuration = (int) ($this->config['frame_duration'] ?? 1);
        $this->textPrefix = $config['text_prefix'] ?? '• ';

        parent::setConfig($config);

        return $this;
    }

    /**
     * @param CreativeObjectInterface $object
     * @return Model
     * @throws FileNotFoundException
     * @throws \RuntimeException
     */
    public function generate(CreativeObjectInterface $object): Model
    {
        $storage = Storage::disk($this->disk);
        $attachment = $object->getAttachment();

        $now = Carbon::now();
        $finalFileName = sprintf(
            '%s/%s/%s/%s.mp4',
            $now->format('Y'),
            $now->format('m'),
            $now->format('d'),
            'creative_curve_text_animation_video_' . $now->format('dmYHis')
        );

        // Собираем массив перечислений
        $enum = array_map(
            static function (string $text) {
                return trim($text);
            },
            explode(',', $object->getText())
        );

        // Сортируем по длине строки
        usort($enum, static function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        // Генерируем "кадры"
        $files = $this->textEnumCurveImageService
            ->setBoxWidth($this->boxWidth)
            ->setBoxHeight($this->boxHeight)
            ->setFontPath(sprintf('%s/public/fonts/kurale.ttf', base_path()))
            ->setFontSize($this->fontSize)
            ->setTextColor($this->textColor)
            ->setTextOffset($this->textOffset)
            ->setEnumPrefix($this->textPrefix)
            ->setTitle($object->getTitle())
            ->setTextEnum($enum)
            ->generateListImagesEnums()
            ->save($this->disk)
        ;

        $mp4Format = new X264();
        $mp4Format->setAudioCodec("libmp3lame");
        $mp4Format->setKiloBitrate(8580);

        try {
            $videos = [];

            // Из кадров генерируем корткие видосы, которые потом склеим
            foreach ($files as $i => $image) {
                $ffmpeg = 'yandexcloud' === $attachment->disk
                    ? FFMpeg::openUrl($attachment->url(), [])
                    : FFMpeg::fromDisk($attachment->disk)->open($attachment->physicalPath());

                $fileName = sprintf(
                    '%s/%s/%s/%s.mp4',
                    $now->format('Y'),
                    $now->format('m'),
                    $now->format('d'),
                    'creative_curve_text_animation_video_part_' . $now->format('dmYHis') . $i
                );

                $startTime = TimeCode::fromSeconds($this->frameDuration * $i);
                $durationTime = TimeCode::fromSeconds(
                    $i === count($files) - 1
                        ? $this->duration - ($this->frameDuration * count($files))
                        : $this->frameDuration
                );

                $ffmpeg
                    ->addWatermark(function (WatermarkFactory $watermark) use ($image) {
                        $watermark->fromDisk($this->disk)
                            ->open($image)
                            ->left($this->positionX)
                            ->top($this->positionY)
                        ;
                    })
                    ->export()
                    ->toDisk($this->disk)
                    ->inFormat($mp4Format)
                    ->addFilter(function (VideoFilters $filters) use ($startTime, $durationTime) {
                        $filters->clip($startTime, $durationTime);
                    })
                    ->save($fileName)
                ;

                $videos[] = $fileName;
                $storage->delete($image);
            }

            FFMpeg::fromDisk($this->disk)
                ->open($videos)
                ->export()
                ->concatWithoutTranscoding()
                ->save($finalFileName)
            ;

            foreach ($videos as $video) {
                $storage->delete($video);
            }
        } catch (EncodingException $exception) {
            $msg = 'ffmpeg_error: ' . $exception->getCommand() . $exception->getErrorOutput();
            \Log::error($msg);
            throw new \RuntimeException($msg);
        }

        return $this->attachmentRepository->createFromDiskAndPath($this->disk, $finalFileName);
    }

    public function getFieldsClass(): string
    {
        return OnePageCurveTextAnimationVideoFields::class;
    }
}