<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\Enum;

/**
 * @method static TodoStatus OPEN()
 * @method static TodoStatus DONE()
 * @method static TodoStatus EXPIRED()
 * @method static TodoStatus CANCELED()
 */
final class TodoStatus extends Enum
{
    public const OPEN = 'open';
    public const DONE = 'done';
    public const EXPIRED = 'expired';
    public const CANCELED = 'canceled';
}
