<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use TodoApp\Application\Command\PostTodo;
use TodoApp\Domain\Model\Todo\TodoDate;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\Todo\TodoText;

class PostTodoCommandResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return PostTodo::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $id = TodoId::fromString($request->request->get('todoId'));
        $text = TodoText::fromString($request->request->get('text'));
        $status = TodoStatus::byValue($request->request->get('status'));
        $date = TodoDate::fromString($request->request->get('status'));

        yield new PostTodo(
            $id,
            $text,
            $status,
            $date
        );
    }
}
