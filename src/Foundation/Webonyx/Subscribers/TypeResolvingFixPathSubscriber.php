<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Subscribers;

use Illuminate\Support\Arr;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Resolver\TypeResolve;
use Railt\Foundation\Webonyx\Input;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Fix of https://github.com/webonyx/graphql-php/issues/396
 * Reproduced to Webonyx version < 0.12.6 (including)
 */
class TypeResolvingFixPathSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $indexes = [];

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TypeResolve::class      => ['onTypeResolve', 100],
            ConnectionClosed::class => ['onConnectionClose', 100],
        ];
    }

    /**
     * @param TypeResolve $event
     */
    public function onTypeResolve(TypeResolve $event): void
    {
        //
        // The error is associated with List types only.
        // Skip coercion if a singular value is returned.
        //
        if (! $event->getFieldDefinition()->isList()) {
            return;
        }

        //
        // Read and update path indexes
        //
        [$input, $identifier] = [$event->getInput(), $this->getIndexIdentifier($event)];

        $index = Arr::get($this->indexes, $identifier, 0);

        Arr::set($this->indexes, $identifier, $index + 1);

        /** @var InputInterface $input */
        $input->withPathChunks(\array_merge($input->getPathChunks(), [$index]));
    }

    /**
     * @param TypeResolve $event
     * @return string
     */
    private function getIndexIdentifier(TypeResolve $event): string
    {
        return Input::chunksToPath([
            $event->getConnection()->getId(),
            $event->getRequest()->getId(),
            $event->getInput()->getPath(),
        ]);
    }

    /**
     * @param ConnectionClosed $event
     */
    public function onConnectionClose(ConnectionClosed $event): void
    {
        $id = $event->getConnection()->getId();

        if (isset($this->indexes[$id])) {
            unset($this->indexes[$id]);
        }
    }
}
