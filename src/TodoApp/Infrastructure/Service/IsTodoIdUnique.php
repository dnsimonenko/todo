<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\Service;

use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoViewRepository;

class IsTodoIdUnique
{
    /** @var TodoViewRepository */
    private $todoViewRepository;

    public function __construct(TodoViewRepository $todoViewRepository)
    {
        $this->todoViewRepository = $todoViewRepository;
    }

    public function __invoke(TodoId $id): bool
    {
        return (bool) $this->todoViewRepository->get($id);
    }
}
