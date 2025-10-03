<?php

namespace App\Builders;

class NoteJsonSimple implements NoteJsonInterface
{
    protected array $data = [];

    // Funciones que me retornarán los datos que requiero para hacer la nota avanzada

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
    }

    public function addCreatedAt(string $createdAt): void
    {
    }

    public function addUpdatedAt(string $updatedAt): void
    {
    }

    public function addEdited(bool $wasEdited): void
    {
    }

    public function addImportant(bool $important): void
    {
    }

    public function addReminder(string $date = null): void
    {
    }

    public function getResult(): string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
    
}