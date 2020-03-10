<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\Enum;

/**
 * @method static TodoStatus OPEN()
 * @method static TodoStatus DONE()
 * @method static TodoStatus CLOSED()
 */
final class TodoStatus extends Enum
{
    public const OPEN = 'open';
    public const DONE = 'done';
    public const CLOSED = 'closed';
}
