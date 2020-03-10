<?php

namespace TodoApp\Domain\Model;

abstract class AggregateRoot implements RecordsEvents, IsEventSourced
{
    /**
     * @var DomainEvent[]
     */
    private $recordedEvents = [];

    public function getRecordedEvents(): DomainEvents
    {
        return new DomainEvents($this->recordedEvents);
    }

    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }

    protected function recordThat(DomainEvent $event)
    {
        $this->recordedEvents[] = $event;
    }

    protected function applyAndRecordThat(DomainEvent $aDomainEvent)
    {
        $this->recordThat($aDomainEvent);

        $this->apply($aDomainEvent);
    }

    public static function reconstituteFrom(AggregateHistory $aggregateHistory)
    {
        $instance = new static();
        foreach ($aggregateHistory as $event) {
            $instance->apply($event);
        }

        return $instance;
    }

    abstract protected function apply(DomainEvent $event): void;

    protected function determineEventHandlerMethodFor(DomainEvent $event): string
    {
        return 'apply' . \implode(\array_slice(\explode('\\', \get_class($event)), -1));
    }
}
