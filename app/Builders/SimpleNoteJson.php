<?php

   namespace App\Builders;

   class SimpleNoteJson implements NoteJsonInterface
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
            $this->data['Contenido'] = $content;
        }

        public function addAuthor(int $userId): void
        {
            // No lo usaremos para la nota simple
        }

        public function addCreatedAt(string $createdAt): void
        {
            // No lo usaremos para la nota simple
        }

        public function addUpdatedAt(string $updatedAt): void
        {
            // No lo usaremos para la nota simple
        }

        public function addEdited(bool $wasEdited): void
        {
            // No lo usaremos para la nota simple
        }

        public function addImportant(bool $important): void
        {
            // No lo usaremos para la nota simple
        }

        public function addReminder(string $date = null): void
        {
            // No lo usaremos para la nota simple
        }

        public function getResult(): string
        {
            return json_encode($this->data, JSON_PRETTY_PRINT);
        }
   }