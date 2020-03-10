<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\AggregateRoot;
use TodoApp\Domain\Model\DomainEvent;
use TodoApp\Domain\Model\Todo\Event\TodoClosed;
use TodoApp\Domain\Model\Todo\Event\TodoMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoPosted;
use TodoApp\Domain\Model\Todo\Event\TodoReopened;
use TodoApp\Domain\Model\Todo\Exception\TodoAlreadyClosed;
use TodoApp\Domain\Model\Todo\Exception\TodoNotDone;
use TodoApp\Domain\Model\Todo\Exception\TodoNotOpen;

final class Todo extends AggregateRoot
{
    /** @var TodoId */
    private $todoId;

    /** @var TodoText */
    private $text;

    /** @var TodoStatus */
    private $status;

    /** @var TodoDate */
    private $date;

    private function __construct()
    {
    }

    public static function post(TodoId $todoId, TodoText $text, TodoStatus $status, TodoDate $date): Todo
    {
        $self = new self();
        $self->todoId = $todoId;
        $self->text = $text;
        $self->status = $status;
        $self->date = $date;

        $self->recordThat(new TodoPosted($todoId, $text, $status, $date));

        return $self;
    }

    public function markAsDone(): void
    {
        $status = TodoStatus::DONE();

        if (!$this->status->equals(TodoStatus::OPEN())) {
            throw new TodoNotOpen();
        }

        $this->applyAndRecordThat(new TodoMarkedAsDone($this->todoId, $this->status, $status));
    }

    public function reopenTodo(): void
    {
        $status = TodoStatus::OPEN();

        if (!$this->status->equals(TodoStatus::DONE())) {
            throw new TodoNotDone();
        }

        $this->applyAndRecordThat(new TodoReopened($this->todoId, $this->status, $status));
    }

    public function closeTodo(): void
    {
        $status = TodoStatus::CLOSED();

        if ($this->status->equals(TodoStatus::CLOSED())) {
            throw new TodoAlreadyClosed();
        }

        $this->applyAndRecordThat(new TodoClosed($this->todoId, $this->status, $status));
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

    private function applyTodoPosted(TodoPosted $event): void
    {
        $this->todoId = $event->todoId();
        $this->text = $event->text();
        $this->status = $event->status();
        $this->date = $event->date();
    }

    private function applyTodoMarkedAsDone(TodoMarkedAsDone $event): void
    {
        $this->status = $event->newStatus();
    }

    private function applyTodoReopened(TodoReopened $event): void
    {
        $this->status = $event->newStatus();
    }

    private function applyTodoClosed(TodoClosed $event): void
    {
        $this->status = $event->newStatus();
    }
}
