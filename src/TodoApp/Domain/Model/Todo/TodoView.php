<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

class TodoView
{
    /** @var string */
    private $todoId;

    /** @var string */
    private $text;

    /** @var string */
    private $status;

    /** @var string */
    private $date;

    public function __construct(
        string $todoId,
        string $text,
        string $status,
        string $date
    ) {
        $this->todoId = $todoId;
        $this->text = $text;
        $this->status = $status;
        $this->date = $date;
    }

    public function todoId(): string
    {
        return $this->todoId;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function date(): string
    {
        return $this->date;
    }
}
