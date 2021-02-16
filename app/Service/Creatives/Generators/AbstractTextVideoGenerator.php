<?php

declare(strict_types=1);

namespace App\Service\Creatives\Generators;

use App\DTO\CreativeObjectInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractTextVideoGenerator implements CreativeGeneratorInterface
{
    /**
     * @var int
     */
    protected $positionX;

    /**
     * @var int
     */
    protected $positionY;

    /**
     * @var int
     */
    protected $boxWidth;

    /**
     * @var int
     */
    protected $boxHeight;

    /**
     * @var int
     */
    protected $fontSize;

    /**
     * @var string
     */
    protected $textColor;

    /**
     * @var int
     */
    protected $textOffset;

    /**
     * @var string
     */
    protected $disk;

    /**
     * AbstractTextVideoGenerator constructor.
     */
    public function __construct()
    {
        $this->disk = config('creatives.disk');
    }

    public function setConfig(array $config): CreativeGeneratorInterface
    {
        $this->positionX = (int) ($config['position_x'] ?? 0);
        $this->positionY = (int) ($config['position_y'] ?? 0);
        $this->boxWidth = (int) ($config['box_width'] ?? 1080);
        $this->boxHeight = (int) ($config['box_height'] ?? 640);
        $this->fontSize = (int) ($config['font_size'] ?? 60);
        $this->textColor = trim($config['text_color'] ?? '#ffffff');
        $this->textOffset = (int) ($config['text_offset'] ?? 0);

        return $this;
    }

}