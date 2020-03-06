<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\EventStore;

use TodoApp\Domain\Model\AggregateHistory;
use TodoApp\Domain\Model\DomainEvents;
use TodoApp\Domain\Model\IdentifiesAggregate;

interface EventStore
{
    public function commit(DomainEvents $events): void;

    public function getAggregateHistoryFor(IdentifiesAggregate $id): AggregateHistory;
}
