<?php

declare(strict_types=1);

namespace App\DTO;

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

    public function setTitle(string $title): CreativeObjectInterface
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

    public function getText(): ?string
    {
        return $this->text ?? null;
    }

    public function setAdditionalParameters(array $parameters): CreativeObjectInterface
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

}