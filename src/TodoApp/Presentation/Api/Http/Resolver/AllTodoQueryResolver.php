<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use TodoApp\Application\Query\AllTodoQuery;

class AllTodoQueryResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return AllTodoQuery::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $query = new AllTodoQuery();

        yield $query;
    }
}
