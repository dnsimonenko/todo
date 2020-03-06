<?php declare(strict_types=1);

namespace TodoApp\Application\Command;

use TodoApp\Domain\Model\Todo\TodoDate;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\Todo\TodoText;

final class PostTodo
{
    /** @var TodoId */
    private $todoId;

    /** @var TodoText */
    private $text;

    /** @var TodoStatus */
    private $status;

    /** @var TodoDate */
    private $date;

    public function __construct(
        TodoId $todoId,
        TodoText $text,
        TodoStatus $status,
        TodoDate $date
    ) {
        $this->todoId = $todoId;
        $this->text = $text;
        $this->status = $status;
        $this->date = $date;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function text(): TodoText
    {
        return $this->text;
    }

    public function status(): TodoStatus
    {
        return $this->status;
    }

    public function date(): TodoDate
    {
        return $this->date;
    }
}
