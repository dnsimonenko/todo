<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Service;

use TodoApp\Domain\Model\Todo\TodoId;

interface IsTodoIdUnique
{
    public function __invoke(TodoId $id): bool;
}
