<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\CommandHandler;

use TodoApp\Domain\Model\Todo\Command\PostTodo;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoRepository;

class PostTodoHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(PostTodo $command): void
    {
        $todo = Todo::post(
            $command->todoId(),
            $command->text(),
            $command->status(),
            $command->date()
        );

        $this->todoRepository->save($todo);
    }
}
