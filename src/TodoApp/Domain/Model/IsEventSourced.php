<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface IsEventSourced
{
    public static function reconstituteFrom(AggregateHistory $aggregateHistory);
}
