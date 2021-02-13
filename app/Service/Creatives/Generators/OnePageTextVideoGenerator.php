<?php

declare(strict_types=1);

namespace App\Service\Creatives\Generators;

use App\DTO\CreativeObjectInterface;
use App\Repository\Orchid\AttachmentRepository;
use App\Service\Creatives\CreativeFieldsContainer;
use App\Service\Creatives\Fields\OnePageTextVideoFields;
use App\Service\TextImage\TextMultilineImageService;
use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\FileNotFoundException;
use Orchid\Attachment\Models\Attachment;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;
use Storage;

class OnePageTextVideoGenerator implements CreativeGeneratorInterface, CreativeFieldsContainer
{
    /**
     * @var string
     */
    private $disk;

    /**
     * @var TextMultilineImageService
     */
    private $textImageService;

    /**
     *
     * @var AttachmentRepository
     */
    private $attachmentRepository;

    /**
     * @var int
     */
    private $boxWidth;

    /**
     * @var int
     */
    private $boxHeight;

    /**
     * @var int
     */
    private $fontSize;

    /**
     * @var string
     */
    private $textColor;

    /**
     * @var int
     */
    private $lineMaxLength;

    /**
     * @var int
     */
    private $textOffset;

    /**
     * @var int
     */
    private $positionX;

    /**
     * @var int
     */
    private $positionY;

    public function __construct(TextMultilineImageService $textImageService, AttachmentRepository $attachmentRepository)
    {
        $this->textImageService = $textImageService;
        $this->attachmentRepository = $attachmentRepository;

        $this->disk = config('creatives.disk');
    }

    public function setConfig(array $config): CreativeGeneratorInterface
    {
        $this->boxWidth = (int) ($config['box_width'] ?? 1080);
        $this->boxHeight = (int) ($config['box_height'] ?? 640);
        $this->fontSize = (int) ($config['font_size'] ?? 60);
        $this->textColor = trim($config['text_color'] ?? '#ffffff');
        $this->lineMaxLength = (int) ($config['line_max_length'] ?? 45);
        $this->textOffset = (int) ($config['text_offset'] ?? 0);
        $this->positionX = (int) ($config['position_x'] ?? 0);
        $this->positionY = (int) ($config['position_y'] ?? 0);

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
        $fileName = sprintf(
            '%s/%s/%s/%s.mp4',
            $now->format('Y'),
            $now->format('m'),
            $now->format('d'),
            'creative_from_model_video_' . $now->format('dmYHis')
        );

        $image = $this->textImageService
            ->setBoxWidth($this->boxWidth)
            ->setBoxHeight($this->boxHeight)
            ->setFontPath(sprintf('%s/public/fonts/kurale.ttf', base_path()))
            ->setFontSize($this->fontSize)
            ->setTextColor($this->textColor)
            ->setLineMaxLength($this->lineMaxLength)
            ->setTextOffset($this->textOffset)
            ->setText($object->getText())
            ->generate()
            ->save($this->disk)
        ;

        $mp4Format = new X264();
        $mp4Format->setAudioCodec("libmp3lame");
        $mp4Format->setKiloBitrate(8580);

        try {
            if ('yandexcloud' === $attachment->disk) {
                $ffmpeg = FFMpeg::openUrl($attachment->url(), []);
            } else {
                $ffmpeg = FFMpeg::fromDisk($attachment->disk)->open($attachment->physicalPath());
            }

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
                ->save($fileName)
            ;
        } catch (EncodingException $exception) {
            $msg = 'ffmpeg_error: ' . $exception->getCommand() . $exception->getErrorOutput();
            \Log::error($msg);
            throw new \RuntimeException($msg);
        }

        $storage->delete($image);

        return $this->attachmentRepository->createFromDiskAndPath($this->disk, $fileName);
    }

    public function getFieldsClass(): string
    {
        return OnePageTextVideoFields::class;
    }

}