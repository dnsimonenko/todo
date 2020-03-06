<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

final class TodoDate
{
    /** @var \DateTimeImmutable */
    private $date;

    public static function fromString(string $date): TodoDate
    {
        return new self($date);
    }

    private function __construct(string $date)
    {
        $this->date = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
    }
}
