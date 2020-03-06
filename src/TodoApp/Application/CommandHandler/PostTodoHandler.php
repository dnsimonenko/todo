<?php declare(strict_types=1);

namespace TodoApp\Application\CommandHandler;

use TodoApp\Application\Command\PostTodo;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoRepository;

class PostTodoHandler
{
    /** @var TodoRepository */
    private $postRepository;

    public function __construct(TodoRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function __invoke(PostTodo $command): void
    {
        $todo = Todo::post(
            $command->todoId(),
            $command->text(),
            $command->status(),
            $command->date()
        );

        $this->postRepository->save($todo);
    }
}
