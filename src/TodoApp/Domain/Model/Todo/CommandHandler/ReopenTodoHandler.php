<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\CommandHandler;

use TodoApp\Domain\Model\Todo\Command\ReopenTodo;
use TodoApp\Domain\Model\Todo\TodoRepository;

class ReopenTodoHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(ReopenTodo $command): void
    {
        $todo = $this->todoRepository->get($command->todoId());
        $todo->reopenTodo();

        $this->todoRepository->save($todo);
    }
}
