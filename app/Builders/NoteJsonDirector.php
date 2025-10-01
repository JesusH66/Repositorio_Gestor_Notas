<?php

   namespace App\Builders;

   class NoteJsonDirector
   {
       private NoteJsonInterface $builder;

       public function setBuilder(NoteJsonInterface $builder): void
       {
           $this->builder = $builder;
       }

       public function buildSimpleJson(array $noteData): string
       {
           $this->builder->reset();
           $this->builder->addTitle($noteData['title']);
           $this->builder->addContent($noteData['content']);
           return $this->builder->getResult();
       }
   }