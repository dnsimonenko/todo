<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TodoApp\Application\Query\AllTodoQuery;
use TodoApp\Application\QueryHandler\AllTodoQueryHandler;

class GetAllTodoAction
{
    /**
     * @Route("/todo", methods={"GET"}, name="get_all_todo")
     */
    public function __invoke(
        AllTodoQuery $query,
        AllTodoQueryHandler $handler,
        ArrayTransformerInterface $transformer
    ) {
        $response = $handler($query);

        return new JsonResponse($transformer->toArray($response), Response::HTTP_OK);
    }
}
