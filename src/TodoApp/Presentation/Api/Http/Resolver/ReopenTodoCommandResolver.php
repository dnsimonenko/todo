<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use TodoApp\Domain\Model\Todo\Command\ReopenTodo;
use TodoApp\Domain\Model\Todo\TodoId;

class ReopenTodoCommandResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return ReopenTodo::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $id = $request->attributes->get('todoId');
        if ($id instanceof TodoId) {
            $command = new ReopenTodo($id);
        } else {
            $command = new ReopenTodo(TodoId::fromString($id));
        }

        yield $command;
    }
}
