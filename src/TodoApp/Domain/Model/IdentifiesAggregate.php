<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

interface IdentifiesAggregate
{
    public static function generate(): IdentifiesAggregate;

    public static function fromString(string $string): self;

    public function __toString(): string;

    public function equals(IdentifiesAggregate $other): bool;
}
