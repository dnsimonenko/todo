<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\AggregateHistory;
use TodoApp\Domain\Model\DomainEvent;
use TodoApp\Domain\Model\DomainEvents;
use TodoApp\Domain\Model\IsEventSourced;
use TodoApp\Domain\Model\RecordsEvents;
use TodoApp\Domain\Model\Todo\Event\TodoPosted;

final class Todo implements RecordsEvents, IsEventSourced
{
    /**
     * @var DomainEvent[]
     */
    private $recordedEvents = [];

    /** @var TodoId */
    private $todoId;

    /** @var TodoText */
    private $text;

    /** @var TodoStatus */
    private $status;

    /** @var TodoDate */
    private $date;

    public function __construct(
        TodoId $todoId,
        TodoText $text,
        TodoStatus $status,
        TodoDate $date
    ) {
        $this->todoId = $todoId;
        $this->text = $text;
        $this->status = $status;
        $this->date = $date;
    }

    public static function post(TodoId $todoId, TodoText $text, TodoStatus $status, TodoDate $date): Todo
    {
        $self = new self($todoId, $text, $status, $date);

        $self->recordThat(new TodoPosted($todoId, $text, $status, $date));

        return $self;
    }

    public function getRecordedEvents(): DomainEvents
    {
        return new DomainEvents($this->recordedEvents);
    }

    private function recordThat(DomainEvent $event)
    {
        $this->recordedEvents[] = $event;
    }

    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }

    public static function reconstituteFrom(AggregateHistory $aggregateHistory)
    {
        $instance = new static();
        foreach ($aggregateHistory as $event) {
            $instance->apply($event);
        }

        return $instance;
    }

    protected function apply(DomainEvent $event): void
    {
        $handler = $this->determineEventHandlerMethodFor($event);

        if (!\method_exists($this, $handler)) {
            throw new \RuntimeException(
                \sprintf(
                    'Missing event handler method %s for aggregate %s',
                    $handler,
                    \get_class($this)
                )
            );
        }

        $this->{$handler}($event);
    }

    protected function determineEventHandlerMethodFor(DomainEvent $event): string
    {
        return 'apply' . \implode(\array_slice(\explode('\\', \get_class($event)), -1));
    }

    private function applyTodoPosted(TodoPosted $event): void
    {
        $this->todoId = $event->todoId();
        $this->text = $event->text();
        $this->status = $event->status();
        $this->date = $event->date();
    }
}
