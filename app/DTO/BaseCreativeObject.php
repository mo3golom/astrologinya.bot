<?php

declare(strict_types=1);

namespace App\DTO;

use Orchid\Attachment\Models\Attachment;

class BaseCreativeObject implements CreativeObjectInterface
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $text;

    /**
     * @var array|null
     */
    private $additionalParameters;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var Attachment
     */
    private $attachment;

    /**
     * @var int
     */
    private $id;

    public function setId(int $id): CreativeObjectInterface
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setTitle(?string $title): CreativeObjectInterface
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title ?? null;
    }

    public function setText(string $text): CreativeObjectInterface
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setAdditionalParameters(?array $parameters): CreativeObjectInterface
    {
        $this->additionalParameters = $parameters;

        return $this;
    }

    public function getAdditionalParameters(): ?array
    {
        return $this->additionalParameters ?? null;
    }

    public function setEntityName(string $entityName): CreativeObjectInterface
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function setAttachment(Attachment $attachment): CreativeObjectInterface
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getAttachment(): Attachment
    {
        return $this->attachment;
    }
}