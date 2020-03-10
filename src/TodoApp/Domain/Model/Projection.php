<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface Projection
{
    public function project(DomainEvents $events);
}
