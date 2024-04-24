<?php

namespace Entity;

use Traits\Entity\Id;
use Traits\Entity\Name;

class Book
{
    use Id, Name;

    protected int $author;

    protected ?string $date;

    public function setAuthor(int $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): int
    {
        return $this->author;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
