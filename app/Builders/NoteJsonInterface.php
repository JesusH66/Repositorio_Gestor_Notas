<?php

   namespace App\Builders;

   interface NoteJsonInterface
   {
       public function reset(): void;
       public function addTitle(string $title): void;
       public function addContent(string $content): void;
       public function getResult(): string; // Como lo mostraré en un modal lo mandamos como string
   }