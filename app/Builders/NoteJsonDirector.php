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

    public function buildIntermediateJson(array $noteData): string
    {
        $this->builder->reset();
        $this->builder->addTitle($noteData['title']);
        $this->builder->addContent($noteData['content']);
        $this->builder->addAuthor($noteData['user_id']);
        $this->builder->addCreatedAt($noteData['created_at']);
        return $this->builder->getResult();
    }

    public function buildAdvancedJson(array $noteData, bool $wasEdited): string
    {
        $this->builder->reset();
        $this->builder->addTitle($noteData['title']);
        $this->builder->addContent($noteData['content']);
        $this->builder->addAuthor($noteData['user_id']);
        $this->builder->addCreatedAt($noteData['created_at']);
        $this->builder->addUpdatedAt($noteData['updated_at']);
        $this->builder->addEdited($wasEdited);
        $this->builder->addImportant($noteData['important']);
        $this->builder->addReminder($noteData['date'] ?? null);
        return $this->builder->getResult();
    }
   }