<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\TodoId;

final class ReopenTodo
{
    /** @var TodoId */
    private $todoId;

    public function __construct(
        TodoId $todoId
    ) {
        $this->todoId = $todoId;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }
}
