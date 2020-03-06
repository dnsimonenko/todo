<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use TodoApp\Application\Command\PostTodo;
use TodoApp\Application\CommandHandler\PostTodoHandler;
use Symfony\Component\Routing\Annotation\Route;

class PostTodoAction
{
    /**
     * @Route("/todo", methods={"POST"})
     */
    public function __invoke(
        PostTodo $command,
        PostTodoHandler $handler
    ) {
        $handler($command);

        return new JsonResponse('', Response::HTTP_OK);
    }
}
