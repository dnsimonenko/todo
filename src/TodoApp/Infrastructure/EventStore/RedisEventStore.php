<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\EventStore;

use JMS\Serializer\SerializerInterface;
use Predis\ClientInterface;
use TodoApp\Domain\Model\AggregateHistory;
use TodoApp\Domain\Model\DomainEvents;
use TodoApp\Domain\Model\IdentifiesAggregate;

class RedisEventStore implements EventStore
{
    /**
     * @var ClientInterface
     */
    private $predis;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ClientInterface $predis, SerializerInterface $serializer)
    {
        $this->predis = $predis;
        $this->serializer = $serializer;
    }

    public function commit(DomainEvents $events): void
    {
        foreach ($events as $event) {
            $eventType = get_class($event);
            $data = $this->serializer->serialize($event, 'json');

            $serialized = $this->serializer->serialize(
                [
                    'type' => $eventType,
                    'created_on' => (new \DateTimeImmutable())->format('YmdHis'),
                    'data' => $data
                ],
                'json'
            );

            $this->predis->rpush($this->computeHashFor($event->getAggregateId()), $serialized);
        }
    }

    public function getAggregateHistoryFor(IdentifiesAggregate $id): AggregateHistory
    {
        $serializedEvents = $this->predis->lrange($this->computeHashFor($id), 0, -1);

        $eventStream = [];

        foreach ($serializedEvents as $serializedEvent) {
            $eventData = $this->serializer->deserialize($serializedEvent, 'array', 'json');
            $eventStream[] = $this->serializer->deserialize($eventData['data'], $eventData['type'], 'json');
        }

        return new AggregateHistory($id, $eventStream);
    }

    private function computeHashFor(IdentifiesAggregate $aggregateId)
    {
        return sprintf('events:%s', $aggregateId);
    }
}
