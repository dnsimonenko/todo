<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface DomainEvent
{
    public function getAggregateId(): IdentifiesAggregate;
}
