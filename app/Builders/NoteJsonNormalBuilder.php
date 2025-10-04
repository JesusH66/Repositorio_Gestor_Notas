<?php
namespace App\Builders;

use InvalidArgumentException;
use Illuminate\Support\Carbon;

class NoteJsonNormalBuilder implements NoteJsonInterface
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

    public function produceUserId($userId): void
    {
        $this->data['user_id'] = $userId;
    }

    public function produceTitle(string $title): void
    {
        $this->data['title'] = $title;
    }

    public function produceContent(string $content): void
    {
        $this->data['content'] = $content;
    }

    public function produceCreatedAt(?string $createdAt): void
    {
        $this->data['created_at'] = $createdAt ?? Carbon::now()->toDateTimeString();
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
        $this->produceUserId($noteData['user_id']);
        $this->produceCreatedAt($noteData['created_at']);
        $result = json_encode($this->data, JSON_PRETTY_PRINT);
        $this->reset();
        return $result;
    }




}