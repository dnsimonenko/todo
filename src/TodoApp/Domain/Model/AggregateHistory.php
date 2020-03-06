<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

final class AggregateHistory extends DomainEvents
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    public function __construct(IdentifiesAggregate $aggregateId, array $events)
    {
        /** @var $event DomainEvent */
        foreach ($events as $event) {
            if (!$event->getAggregateId()->equals($aggregateId)) {
                throw new CorruptAggregateHistory;
            }
        }
        parent::__construct($events);
        $this->aggregateId = $aggregateId;
    }

    public function getAggregateId(): IdentifiesAggregate
    {
        return $this->aggregateId;
    }

    public function append(DomainEvent $domainEvent): self
    {
    }
}
