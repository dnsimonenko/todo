<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\CommandHandler;

use TodoApp\Domain\Model\Todo\Command\MarkTodoAsDone;
use TodoApp\Domain\Model\Todo\TodoRepository;

class MarkTodoAsDoneHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(MarkTodoAsDone $command): void
    {
        $todo = $this->todoRepository->get($command->todoId());
        $todo->markAsDone();

        $this->todoRepository->save($todo);
    }
}
