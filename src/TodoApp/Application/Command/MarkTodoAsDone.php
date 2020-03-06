<?php declare(strict_types=1);

namespace TodoApp\Application\Command;

use TodoApp\Domain\Model\Todo\TodoId;

final class MarkTodoAsDone
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
