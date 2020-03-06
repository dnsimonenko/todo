<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TodoApp\Domain\Model\IdentifiesAggregate;

final class TodoId implements IdentifiesAggregate
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): IdentifiesAggregate
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $todoId): IdentifiesAggregate
    {
        return new self(Uuid::fromString($todoId));
    }

    public function __toString(): string
    {
        return (string) $this->uuid;
    }

    public function equals(IdentifiesAggregate $other): bool
    {
        return $other instanceof TodoId && $this->uuid === $other->uuid;
    }
}
