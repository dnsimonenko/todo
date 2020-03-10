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
    private $id;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
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
        return (string) $this->id;
    }

    public function equals(IdentifiesAggregate $other): bool
    {
        return $other instanceof TodoId && $this->id->equals($other->id);
    }
}
