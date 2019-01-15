<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Subscribers;

use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DefaultResponseSubscriber
 */
class DefaultResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FieldResolve::class => ['onFieldResolve', -100],
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function onFieldResolve(FieldResolve $event): void
    {
        if ($event->hasResult()) {
            return;
        }

        $default = function ($result) use ($event): void {
            $event->withParentResult($result);
        };

        if ($result = Attribute::read($event->getParentResult(), $this->getFieldName($event), $default)) {
            $event->withResult($result);
        }
    }

    /**
     * @param FieldResolve $event
     * @return string
     */
    private function getFieldName(FieldResolve $event): string
    {
        return $event->getFieldDefinition()->getName();
    }
}
