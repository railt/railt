<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug\Formatter;

use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\ResponseInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PrettyResponseSubscriber
 */
class PrettyResponseSubscriber implements EventSubscriberInterface
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
        $response = $event->getResponse();

        if ($response instanceof ResponseInterface) {
            $this->formatResponse($response);
            $this->shareExceptionTrace($response);
        }
    }

    /**
     * @param ResponseInterface $response
     */
    private function formatResponse(ResponseInterface $response): void
    {
        $response->withJsonOptions(\JSON_PRETTY_PRINT);
    }

    /**
     * Toggles all exceptions in response to debug mode.
     *
     * @param ResponseInterface $response
     */
    private function shareExceptionTrace(ResponseInterface $response): void
    {
        foreach ($response->getExceptions() as $exception) {
            if ($exception instanceof GraphQLExceptionInterface) {
                $exception->withExtension(new ExceptionTraceExtension($exception));
            }

            $exception->publish();
        }
    }
}
