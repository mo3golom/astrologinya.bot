<?php

declare(strict_types=1);

namespace App\Service\TextImage;

abstract class AbstractTextImageService implements TextImageServiceInterface
{
    /**
     * @var int
     */
    protected $boxWidth = 1080;

    /**
     * @var int
     */
    protected $boxHeight = 640;

    /**
     * @var int
     */
    protected $fontSize = 60;

    /**
     * @var string
     */
    protected $fontPath;

    /**
     * @var string
     */
    protected $textColor = '#ffffff';

    /**
     * @param int $boxWidth
     * @return TextImageServiceInterface
     */
    public function setBoxWidth(int $boxWidth): TextImageServiceInterface
    {
        $this->boxWidth = $boxWidth;

        return $this;
    }

    /**
     * @param int $boxHeight
     * @return TextImageServiceInterface
     */
    public function setBoxHeight(int $boxHeight): TextImageServiceInterface
    {
        $this->boxHeight = $boxHeight;

        return $this;
    }

    /**
     * @param int $fontSize
     * @return TextImageServiceInterface
     */
    public function setFontSize(int $fontSize): TextImageServiceInterface
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * @param string $fontPath
     * @return TextImageServiceInterface
     */
    public function setFontPath(string $fontPath): TextImageServiceInterface
    {
        $this->fontPath = $fontPath;

        return $this;
    }

    /**
     * @param string $textColor
     * @return TextImageServiceInterface
     */
    public function setTextColor(string $textColor): TextImageServiceInterface
    {
        $this->textColor = $textColor;

        return $this;
    }
}