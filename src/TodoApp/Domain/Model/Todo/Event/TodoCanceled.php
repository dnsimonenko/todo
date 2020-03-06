<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Event;

use Buttercup\Protects\DomainEvent;
use TodoApp\Domain\Model\IdentifiesAggregate;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;

final class TodoCanceled implements DomainEvent
{
    /** @var TodoId */
    private $todoId;

    /** @var TodoStatus */
    private $status;

    public function __construct(
        TodoId $todoId,
        TodoStatus $status
    ) {
        $this->todoId = $todoId;
        $this->status = $status;
    }

    public function getAggregateId(): IdentifiesAggregate
    {
        return $this->todoId;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function status(): TodoStatus
    {
        return $this->status;
    }
}
