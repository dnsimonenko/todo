<?php declare(strict_types=1);

namespace TodoApp\Application\Query;

class TodoQuery
{
    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
