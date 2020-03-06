<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\Repository;

use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Infrastructure\EventStore\EventStore;

class EventStoreTodoRepository implements TodoRepository
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
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

        $todo->clearRecordedEvents();
    }
}
