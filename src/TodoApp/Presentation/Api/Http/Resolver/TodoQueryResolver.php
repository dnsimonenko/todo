<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use TodoApp\Application\Query\TodoQuery;
use TodoApp\Domain\Model\Todo\TodoId;

class TodoQueryResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return TodoQuery::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $id = $request->attributes->get('todoId');
        if ($id instanceof TodoId) {
            $query = new TodoQuery((string) $id);
        } else {
            $query = new TodoQuery($id);
        }

        yield $query;
    }
}
