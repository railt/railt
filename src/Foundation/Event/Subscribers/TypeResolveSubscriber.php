<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Subscribers;

use Railt\Foundation\Event\Resolver\TypeResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TypeResolveSubscriber
 */
class TypeResolveSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TypeResolve::class => ['onTypeResolve', -100],
        ];
    }

    /**
     * @param TypeResolve $event
     * @return mixed
     */
    public function onTypeResolve(TypeResolve $event): TypeResolve
    {
        if ($event->hasResult()) {
            return $event;
        }

        //
        // Resolve from "__typename" field
        //
        if ($typename = Attribute::read($event->getParentResult(), '__typename')) {
            return $event->withResult($typename);
        }

        return $event;
    }
}
