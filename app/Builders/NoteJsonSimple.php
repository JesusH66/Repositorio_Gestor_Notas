<?php
namespace App\Builders;

use InvalidArgumentException;

class NoteJsonSimple implements NoteJsonInterface
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

    public function buildSimple(array $noteData): void
    {
        $requiredKeys = ['title', 'content'];
        foreach ($requiredKeys as $key) {
            if (!isset($noteData[$key]) || empty(trim($noteData[$key]))) {
                throw new InvalidArgumentException("El campo requerido '$key' no está presente o está vacío");
            }
        }

        $this->reset();
        $this->produceTitle($noteData['title']);
        $this->produceContent($noteData['content']);
    }

    public function getResult(): string
    {
        $requiredKeys = ['title', 'content'];
        foreach ($requiredKeys as $key) {
            if (!isset($this->data[$key]) || empty($this->data[$key])) {
                throw new InvalidArgumentException("El campo requerido '$key' no está establecido");
            }
        }
        $result = json_encode($this->data, JSON_PRETTY_PRINT);
        $this->reset();
        return $result;
    }
}