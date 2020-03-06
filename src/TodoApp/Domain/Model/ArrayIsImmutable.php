<?php declare(strict_types=1);

namespace TodoApp\Domain\Model;

use BadMethodCallException;

final class ArrayIsImmutable extends BadMethodCallException
{
}
