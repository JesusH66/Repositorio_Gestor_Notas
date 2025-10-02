<?php

namespace App\Builders;

class IntermediateNoteJson implements NoteJsonInterface
{
    protected array $data = [];

    public function reset(): void
    {
        $this->data = [];
    }

    public function addTitle(string $title): void
    {
        $this->data['Titulo de la nota'] = $title;
    }

    public function addContent(string $content): void
    {
        $this->data['Descripcion'] = $content;
    }

    public function addAuthor(int $userId): void
    {
        $this->data['Autor'] = "User ID: $userId";
    }

    public function addCreatedAt(string $createdAt): void
    {
        $this->data['Fecha de creacion'] = $createdAt;
    }

    public function addUpdatedAt(string $updatedAt): void
    {
        // No lo usaremos para la nota intermedia
    }

    public function addEdited(bool $wasEdited): void
    {
        // No lo usaremos para la nota intermedia
    }

    public function addImportant(bool $important): void
    {
        // No lo usaremos para la nota intermedia
    }

    public function addReminder(string $date = null): void
    {
        // No lo usaremos para la nota intermedia
    }

    public function getResult(): string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}