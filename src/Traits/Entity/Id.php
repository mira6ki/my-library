<?php

namespace Traits\Entity;

trait Id
{
    protected int $id;

    public function setId(int $id): int
    {
        return $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
