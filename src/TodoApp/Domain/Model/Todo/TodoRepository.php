<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

interface TodoRepository
{
    public function save(Todo $todo): void;

    public function get(TodoId $todoId): ?Todo;
}
