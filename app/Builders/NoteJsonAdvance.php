<?php
namespace App\Builders;

use InvalidArgumentException;
use Illuminate\Support\Carbon;

class NoteJsonAdvance implements NoteJsonInterface
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
        $this->data['updated_at'] = $updatedAt;
    }

    public function produceEdited(bool $wasEdited): void
    {
        $this->data['was_edited'] = $wasEdited;
    }

    public function produceImportant(bool $isImportant): void
    {
        $this->data['is_important'] = $isImportant;
    }

    public function produceReminder(?string $reminder): void
    {
        $this->data['reminder'] = $reminder;
    }

    public function buildAdvanced(array $noteData, bool $wasEdited): void
    {
        $requiredKeys = ['title', 'content', 'user_id', 'created_at', 'updated_at', 'important'];
        foreach ($requiredKeys as $key) {
            
        }

        $this->reset();
        $this->produceTitle($noteData['title']);
        $this->produceContent($noteData['content']);
        $this->produceUserId($noteData['user_id']);
        $this->produceCreatedAt($noteData['created_at']);
        $this->produceUpdatedAt($noteData['updated_at']);
        $this->produceEdited($wasEdited);
        $this->produceImportant($noteData['important']);
        $this->produceReminder($noteData['date'] ?? null);
    }

    public function getResult(): string
    {
        $requiredKeys = ['user_id', 'title', 'content'];
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