<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

interface TodoViewRepository
{
    public function get(string $id): TodoView;

    public function all(): array;
}
