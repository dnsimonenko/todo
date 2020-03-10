<?php declare(strict_types=1);

namespace TodoApp\Application\QueryHandler;

use TodoApp\Application\Query\AllTodoQuery;
use TodoApp\Domain\Model\Todo\TodoViewRepository;

class AllTodoQueryHandler
{
    /** @var TodoViewRepository */
    private $todoRepository;

    public function __construct(TodoViewRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(AllTodoQuery $query): array
    {
        return $this->todoRepository->all();
    }
}
