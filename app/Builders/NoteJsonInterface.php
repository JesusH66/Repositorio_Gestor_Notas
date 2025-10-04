<?php
namespace App\Builders;

interface NoteJsonInterface
{
    public function reset(): void;
    public function produceTitle(string $title): void;
    public function produceContent(string $content): void;
    public function produceUserId($userId): void;
    public function produceCreatedAt(?string $createdAt): void;
    public function produceUpdatedAt(?string $updatedAt): void;
    public function produceEdited(bool $wasEdited): void;
    public function produceImportant(bool $isImportant): void;
    public function produceReminder(?string $reminder): void;
    public function getResult($noteData): string;
}