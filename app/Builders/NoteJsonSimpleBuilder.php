<?php
namespace App\Builders;

use InvalidArgumentException;

class NoteJsonSimpleBuilder implements NoteJsonInterface
{
    private array $data = [];

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function produceTitle(string $title): void
    {
        $this->data['title'] = $title;
    }

    public function produceContent(string $content): void
    {
        $this->data['content'] = $content;
    }

    public function produceUserId($userId): void
    {

    }

    public function produceCreatedAt(?string $createdAt): void
    {

    }

    public function produceUpdatedAt(?string $updatedAt): void
    {
    }

    public function produceEdited(bool $wasEdited): void
    {

    }

    public function produceImportant(bool $isImportant): void
    {

    }

    public function produceReminder(?string $reminder): void
    {

    }


    public function getResult($noteData): string
    {
        $this->reset();
        $this->produceTitle($noteData['title']);
        $this->produceContent($noteData['content']);
        $result = json_encode($this->data, JSON_PRETTY_PRINT);
        $this->reset();
        return $result;
    }

}