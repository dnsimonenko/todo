<?php declare(strict_types=1);

namespace TodoApp\Application\QueryHandler;

use TodoApp\Application\Query\TodoQuery;
use TodoApp\Domain\Model\Todo\TodoView;
use TodoApp\Domain\Model\Todo\TodoViewRepository;

class TodoQueryHandler
{
    /** @var TodoViewRepository */
    private $todoRepository;

    public function __construct(TodoViewRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(TodoQuery $query): TodoView
    {
        return $this->todoRepository->get($query->id());
    }
}
