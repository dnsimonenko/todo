<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Event;

use TodoApp\Domain\Model\DomainEvent;
use TodoApp\Domain\Model\IdentifiesAggregate;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;

final class TodoReopened implements DomainEvent
{
    /** @var TodoId */
    private $todoId;

    /**
     * @var TodoStatus
     */
    private $oldStatus;

    /**
     * @var TodoStatus
     */
    private $newStatus;

    public function __construct(
        TodoId $todoId,
        TodoStatus $oldStatus,
        TodoStatus $newStatus
    ) {
        $this->todoId = $todoId;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function getAggregateId(): IdentifiesAggregate
    {
        return $this->todoId;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function oldStatus(): TodoStatus
    {
        return $this->oldStatus;
    }

    public function newStatus(): TodoStatus
    {
        return $this->newStatus;
    }
}
