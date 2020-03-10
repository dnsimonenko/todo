<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\Projection;
use TodoApp\Domain\Model\Todo\Event\TodoClosed;
use TodoApp\Domain\Model\Todo\Event\TodoMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoPosted;
use TodoApp\Domain\Model\Todo\Event\TodoReopened;

interface TodoProjection extends Projection
{
    public function projectTodoPosted(TodoPosted $event): void;

    public function projectTodoMarkedAsDone(TodoMarkedAsDone $event): void;

    public function projectTodoReopened(TodoReopened $event): void;

    public function projectTodoClosed(TodoClosed $event): void;
}
