<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface RecordsEvents
{
    public function getRecordedEvents(): DomainEvents;

    public function clearRecordedEvents(): void;
}
