<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\MemoryProfiler;

use Railt\Foundation\Event\Http\ResponseProceed;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MemoryProfilerSubscriber
 */
class MemoryProfilerSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseProceed::class => ['onResponse', -100],
        ];
    }

    /**
     * @param ResponseProceed $event
     */
    public function onResponse(ResponseProceed $event): void
    {
        if ($response = $event->getResponse()) {
            $response->withExtension(new MemoryProfilerExtension());
        }
    }
}
