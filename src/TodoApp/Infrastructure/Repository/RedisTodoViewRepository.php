<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\Repository;

use JMS\Serializer\SerializerInterface;
use Predis\ClientInterface;
use TodoApp\Domain\Model\Todo\Exception\TodoNotFound;
use TodoApp\Domain\Model\Todo\TodoView;
use TodoApp\Domain\Model\Todo\TodoViewRepository;

class RedisTodoViewRepository implements TodoViewRepository
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

    public function get(string $id): TodoView
    {
        $todo = $this->predis->hgetall(sprintf('todo:%s', $id));
        if (empty($todo)) {
            throw new TodoNotFound();
        }

        return new TodoView(
            $todo['todoId'],
            $todo['text'],
            $todo['status'],
            $todo['date'],
        );
    }

    public function all(): array
    {
        $todos = [];
        $ids = $this->predis->lrange('todo', 0, -1);

        if (empty($ids)) {
            return $todos;
        }

        foreach ($ids as $id) {
            $todos[] = $this->get(explode(':', $id)[1]);
        }

        return $todos;
    }
}
