<?php declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Exception;

use TodoApp\Domain\Model\DomainException;

class TodoNotFound extends \InvalidArgumentException implements DomainException
{
}
