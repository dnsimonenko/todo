<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\Repository;

use CQRSBlog\BlogEngine\DomainModel\TodoProjection;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Infrastructure\EventStore\EventStore;

class EventStoreTodoRepository implements TodoRepository
{
    /** @var EventStore */
    private $eventStore;

    /** @var TodoProjection */
    private $todoProjection;

    public function __construct(
        EventStore $eventStore,
        TodoProjection $todoProjection
    ) {
        $this->eventStore = $eventStore;
        $this->todoProjection = $todoProjection;
    }

    public function get(TodoId $todoId): Todo
    {
        $history = $this->eventStore->getAggregateHistoryFor($todoId);

        return Todo::reconstituteFrom($history);
    }

    public function save(Todo $todo): void
    {
        $events = $todo->getRecordedEvents();
        $this->eventStore->commit($events);
        $this->todoProjection->project($events);

        $todo->clearRecordedEvents();
    }
}
