<?php

namespace App\Builders;

class NoteJson implements NoteJsonInterface
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
        $this->data['Autor'] = "User ID: $userId";
    }

    public function addCreatedAt(string $createdAt): void
    {
        $this->data['Fecha de creacion'] = $createdAt;
    }

    public function addUpdatedAt(string $updatedAt): void
    {
        $this->data['Edicion de nota'] = $updatedAt;
    }

    public function addEdited(bool $wasEdited): void
    {
        $this->data['Fue editada?'] = $wasEdited;
    }

    public function addImportant(bool $important): void
    {
        $this->data['Importante'] = $important;
    }

    public function addReminder(string $date = null): void
    {
        $this->data['Fecha recordatorio'] = $date;
    }

    public function getResult(): string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
    
}