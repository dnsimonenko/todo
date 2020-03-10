<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TodoApp\Domain\Model\Todo\Command\PostTodo;
use TodoApp\Domain\Model\Todo\CommandHandler\PostTodoHandler;

class PostTodoAction
{
    /**
     * @Route("/todo", methods={"POST"}, name="post_todo")
     */
    public function __invoke(
        PostTodo $command,
        PostTodoHandler $handler
    ) {
        $handler($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
