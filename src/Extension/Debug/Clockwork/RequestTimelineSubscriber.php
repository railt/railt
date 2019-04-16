<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug\Clockwork;

use Clockwork\Clockwork;
use Railt\Component\Http\RequestInterface;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RequestTimelineSubscriber
 */
class RequestTimelineSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private const RAILT_PROCESS_HTTP = 'railt:http:%s';

    /**
     * @var Clockwork
     */
    private $clockwork;

    /**
     * FieldResolveSubscriber constructor.
     *
     * @param Clockwork $clockwork
     */
    public function __construct(Clockwork $clockwork)
    {
        $this->clockwork = $clockwork;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestReceived::class => ['onRequest', 100],
            ResponseProceed::class => ['onResponse', -100],
        ];
    }

    /**
     * @param RequestReceived $event
     */
    public function onRequest(RequestReceived $event): void
    {
        $request = $event->getRequest();

        $this->clockwork->startEvent($this->httpEventKey($request), 'GraphQL Request Execution');
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    private function httpEventKey(RequestInterface $request): string
    {
        return \sprintf(self::RAILT_PROCESS_HTTP, $request->getId());
    }

    /**
     * @param ResponseProceed $event
     */
    public function onResponse(ResponseProceed $event): void
    {
        $request = $event->getRequest();

        $this->clockwork->endEvent($this->httpEventKey($request));
    }
}
