<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\CommandHandler;

use TodoApp\Domain\Model\Todo\Command\CloseTodo;
use TodoApp\Domain\Model\Todo\TodoRepository;

class CloseTodoHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(CloseTodo $command): void
    {
        $todo = $this->todoRepository->get($command->todoId());
        $todo->closeTodo();

        $this->todoRepository->save($todo);
    }
}
