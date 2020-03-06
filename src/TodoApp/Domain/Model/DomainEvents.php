<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

class DomainEvents extends ImmutableArray
{
    protected function guardType($item)
    {
        if (!($item instanceof DomainEvent)) {
            throw new ArrayIsImmutable;
        }
    }
}
