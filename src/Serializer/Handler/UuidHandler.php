<?php

namespace App\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TodoApp\Domain\Model\Enum;
use TodoApp\Domain\Model\Todo\TodoStatus;

class UuidHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = [];

        foreach (['json', 'xml', 'yml'] as $format) {
            $methods[] = [
                'type' => 'uuid',
                'format' => $format,
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'method' => 'serializeUuid',
            ];
            $methods[] = [
                'type' => 'uuid',
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeUuid',
            ];
        }

        return $methods;
    }

    public function serializeUuid(VisitorInterface $visitor, UuidInterface $uuid, array $type, Context $context): string
    {
        return (string) $uuid;
    }

    public function deserializeUuid(VisitorInterface $visitor, $value, array $type): UuidInterface
    {
        return Uuid::fromString($value);
    }
}
