<?php

namespace Model;

interface RepositoryInterface
{
    public function getAll(array $params = []): array;
}
