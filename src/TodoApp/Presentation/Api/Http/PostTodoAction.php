<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use Symfony\Component\HttpFoundation\Response;
use TodoApp\Application\Command\PostTodo;
use TodoApp\Application\CommandHandler\PostTodoHandler;
use FOS\RestBundle\Controller\Annotations as FOS;

class PostTodoAction
{
    /**
     * @FOS\Post("/todo")
     * @FOS\View(statusCode=Response::HTTP_CREATED)
     */
    public function __invoke(
        PostTodo $command,
        PostTodoHandler $handler
    ) {
        $handler($command);
    }
}
