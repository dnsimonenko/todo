<?php declare(strict_types=1);

namespace TodoApp\Presentation\Api\Http;

use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TodoApp\Application\Query\TodoQuery;
use TodoApp\Application\QueryHandler\TodoQueryHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TodoApp\Domain\Model\Todo\TodoId;

class GetTodoAction
{
    /**
     * @Route("/todo/{todoId}", methods={"GET"}, name="get_todo")
     *
     * @ParamConverter("todoId", converter="value_object")
     */
    public function __invoke(
        TodoId $todoId,
        TodoQuery $query,
        TodoQueryHandler $handler,
        ArrayTransformerInterface $transformer
    ) {
        $response = $handler($query);

        return new JsonResponse($transformer->toArray($response), Response::HTTP_OK);
    }
}
