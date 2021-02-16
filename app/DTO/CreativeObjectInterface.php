<?php

namespace App\DTO;

use Orchid\Attachment\Models\Attachment;

/**
 * Интерфейс мостик для передачи данных из геттера сущности в генератор
 *
 * Interface CreativeObjectInterface
 */
interface CreativeObjectInterface
{
    public function setId(string $id): CreativeObjectInterface;

    public function getId(): string;

    public function setTitle(?string $title): CreativeObjectInterface;

    public function getTitle(): ?string;

    public function setText(string $text): CreativeObjectInterface;

    public function getText(): string;

    public function setAdditionalParameters(?array $parameters): CreativeObjectInterface;

    public function getAdditionalParameters(): ?array;

    public function setEntityName(string $entityName): CreativeObjectInterface;

    public function getEntityName(): string;

    public function setAttachment(Attachment $attachment): CreativeObjectInterface;

    public function getAttachment(): Attachment;
}