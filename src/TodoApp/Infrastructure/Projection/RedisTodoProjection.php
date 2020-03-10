<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\Projection;

use CQRSBlog\BlogEngine\DomainModel\TodoProjection;
use JMS\Serializer\SerializerInterface;
use Predis\ClientInterface;
use TodoApp\Domain\Model\DomainEvent;
use TodoApp\Domain\Model\DomainEvents;
use TodoApp\Domain\Model\Todo\Event\TodoClosed;
use TodoApp\Domain\Model\Todo\Event\TodoMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoPosted;
use TodoApp\Domain\Model\Todo\Event\TodoReopened;

class RedisTodoProjection implements TodoProjection
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

    public function project(DomainEvents $events)
    {
        foreach ($events as $event) {
            $handler = $this->determineProjectHandlerMethodFor($event);

            if (!\method_exists($this, $handler)) {
                throw new \RuntimeException(
                    \sprintf(
                        'Missing projections event handler method %s',
                        $handler
                    )
                );
            }

            $this->{$handler}($event);
        }
    }

    public function projectTodoPosted(TodoPosted $event): void
    {
        $anAggregateId = $event->getAggregateId();

        $hash = $this->computeHashFor($anAggregateId);

        $this->predis->hmset(
            $hash,
            [
                'todoId' => $event->todoId(),
                'text' => $event->text(),
                'status' => $event->status(),
                'date' => $event->date(),
            ]
        );

        $this->predis->rpush('posts', $hash);
    }

    public function projectTodoMarkedAsDone(TodoMarkedAsDone $event): void
    {
        $this->predis->hset(
            $this->computeHashFor($event->getAggregateId()),
            'status',
            $event->newStatus()
        );
    }

    public function projectTodoReopened(TodoReopened $event): void
    {
        $this->predis->hset(
            $this->computeHashFor($event->getAggregateId()),
            'status',
            $event->newStatus()
        );
    }

    public function projectTodoClosed(TodoClosed $event): void
    {
        $this->predis->hset(
            $this->computeHashFor($event->getAggregateId()),
            'status',
            $event->newStatus()
        );
    }

    protected function determineProjectHandlerMethodFor(DomainEvent $event): string
    {
        return 'project' . \implode(\array_slice(\explode('\\', \get_class($event)), -1));
    }

    protected function computeHashFor($anAggregateId): string
    {
        return sprintf('todo:%s', $anAggregateId);
    }
}
