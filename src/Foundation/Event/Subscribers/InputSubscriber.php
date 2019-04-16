<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Subscribers;

use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Connection\ProvidesConnection;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Event\Resolver\TypeResolve;
use Railt\Component\Http\Identifiable;
use Railt\Component\Http\InputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class InputSubscriber
 */
class InputSubscriber implements EventSubscriberInterface
{
    /**
     * @var array|InputInterface[]
     */
    private $inputs = [];

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConnectionEstablished::class => ['onConnectionEstablished', 100],
            FieldResolve::class          => ['onFieldResolve', 100],
            TypeResolve::class           => ['onTypeResolve', 100],
            ConnectionClosed::class      => ['onConnectionClosed', 100],
        ];
    }

    /**
     * @param Identifiable $connection
     * @param string $path
     * @return InputInterface|null
     */
    public function get(Identifiable $connection, string $path): ?InputInterface
    {
        return $this->getById($connection->getId(), $path);
    }

    /**
     * @param int $connection
     * @param string $path
     * @return InputInterface|null
     */
    public function getById(int $connection, string $path): ?InputInterface
    {
        $inputs = $this->inputs[$connection] ?? [];

        return $inputs[$path] ?? null;
    }

    /**
     * @param FieldResolve $event
     */
    public function onFieldResolve(FieldResolve $event): void
    {
        $this->rememberInput($event);
    }

    /**
     * @param FieldResolve $event
     */
    private function rememberInput(FieldResolve $event): void
    {
        $input = $event->getInput();

        $this->inputs[$event->getConnection()->getId()][$input->getPath()] = $input;
    }

    /**
     * @param TypeResolve $event
     */
    public function onTypeResolve(TypeResolve $event): void
    {
        if ($input = $this->input($event, $event->getPath())) {
            $event->withInput($input);
        }
    }

    /**
     * @param ProvidesConnection $event
     * @param string $path
     * @return InputInterface|null
     */
    private function input(ProvidesConnection $event, string $path): ?InputInterface
    {
        return $this->inputs($event)[$path] ?? null;
    }

    /**
     * @param ProvidesConnection $event
     * @return array
     */
    private function inputs(ProvidesConnection $event): array
    {
        return $this->inputs[$event->getConnection()->getId()] ?? [];
    }

    /**
     * @param ConnectionEstablished $event
     */
    public function onConnectionEstablished(ConnectionEstablished $event): void
    {
        $this->inputs[$event->getId()] = [];
    }

    /**
     * @param ConnectionClosed $event
     */
    public function onConnectionClosed(ConnectionClosed $event): void
    {
        if (isset($this->inputs[$event->getId()])) {
            unset($this->inputs[$event->getId()]);
        }
    }
}
