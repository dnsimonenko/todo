<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\ValueObject;

final class TodoText
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public static function fromString(string $text): self
    {
        return new self($text);
    }

    public function __toString(): string
    {
        return (string) $this->text;
    }
}
