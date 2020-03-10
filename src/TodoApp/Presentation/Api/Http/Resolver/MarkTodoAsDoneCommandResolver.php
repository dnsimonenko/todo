<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use TodoApp\Domain\Model\Todo\Command\MarkTodoAsDone;
use TodoApp\Domain\Model\Todo\TodoId;

class MarkTodoAsDoneCommandResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return MarkTodoAsDone::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $id = $request->attributes->get('todoId');
        if ($id instanceof TodoId) {
            $command = new MarkTodoAsDone($id);
        } else {
            $command = new MarkTodoAsDone(TodoId::fromString($id));
        }

        yield $command;
    }
}
