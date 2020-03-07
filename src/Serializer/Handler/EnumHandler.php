<?php

namespace App\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use TodoApp\Domain\Model\Enum;

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
                'type' => 'enum',
                'format' => $format,
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'method' => 'serializeEnum',
            ];
            $methods[] = [
                'type' => 'enum',
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeEnum',
            ];
        }

        return $methods;
    }

    public function serializeEnum(VisitorInterface $visitor, Enum $enum, array $type, Context $context): string
    {
        return $enum->getValue();
    }

    public function deserializeEnum(VisitorInterface $visitor, $value, array $type): Enum
    {
        return new $type($value);
    }
}
