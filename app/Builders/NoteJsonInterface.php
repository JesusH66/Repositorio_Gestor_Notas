<?php

   namespace App\Builders;

   interface NoteJsonInterface
   {
        public function reset(): void;
        public function addTitle(string $title): void;
        public function addContent(string $content): void;
        public function addAuthor(int $userId): void;
        public function addCreatedAt(string $createdAt): void;
        public function addUpdatedAt(string $updatedAt): void;
        public function addEdited(bool $wasEdited): void;
        public function addImportant(bool $important): void;
        public function addReminder(string $date = null): void;
        public function getResult(): string;
   }