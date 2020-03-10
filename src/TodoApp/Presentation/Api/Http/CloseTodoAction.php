<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TodoApp\Domain\Model\Todo\Command\CloseTodo;
use TodoApp\Domain\Model\Todo\Command\MarkTodoAsDone;
use TodoApp\Domain\Model\Todo\Command\ReopenTodo;
use TodoApp\Domain\Model\Todo\CommandHandler\CloseTodoHandler;
use TodoApp\Domain\Model\Todo\CommandHandler\MarkTodoAsDoneHandler;
use TodoApp\Domain\Model\Todo\CommandHandler\ReopenTodoHandler;
use TodoApp\Domain\Model\Todo\TodoId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CloseTodoAction
{
    /**
     * @Route("/todo/{todoId}", methods={"DELETE"}, name="close_todo")
     *
     * @ParamConverter("todoId", converter="value_object")
     */
    public function __invoke(
        TodoId $todoId,
        CloseTodo $command,
        CloseTodoHandler $handler
    ) {
        $handler($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
