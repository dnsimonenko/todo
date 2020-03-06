<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface Entity
{
    public function sameIdentityAs(Entity $other): bool;
}
