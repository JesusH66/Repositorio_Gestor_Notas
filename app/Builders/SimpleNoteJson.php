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
           $this->data['title'] = $title;
       }

       public function addContent(string $content): void
       {
           $this->data['content'] = $content;
       }

       public function getResult(): string
       {
           return json_encode($this->data, JSON_PRETTY_PRINT);
       }
   }