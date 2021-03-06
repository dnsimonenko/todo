<?php

namespace App\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use TodoApp\Domain\Model\Enum;
use TodoApp\Domain\Model\Todo\TodoStatus;

class EnumHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = [];

        foreach (['json', 'xml', 'yml'] as $format) {
            $methods[] = [
                'type' => 'TodoStatus',
                'format' => $format,
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'method' => 'serializeTodoStatus',
            ];
            $methods[] = [
                'type' => 'TodoStatus',
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeTodoStatus',
            ];
        }

        return $methods;
    }

    public function serializeTodoStatus(VisitorInterface $visitor, TodoStatus $status, array $type, Context $context): string
    {
        return $status->getValue();
    }

    public function deserializeTodoStatus(VisitorInterface $visitor, $value, array $type): Enum
    {
        return new TodoStatus($value);
    }
}
