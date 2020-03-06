<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface ValueObject
{
    public function sameValueAs(ValueObject $object): bool;
}
