<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Http\Exception\GraphQLException;
use Railt\Http\Extension\DebugExtension as DebugHttpExtension;
use Railt\Http\ResponseInterface;

/**
 * Class DebugExtension
 */
class DebugExtension extends Extension
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Debug extension';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Debug';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return ['railt/railt' => EventsExtension::class];
    }

    /**
     * @param bool $debug
     */
    public function boot(bool $debug = false): void
    {
        $this->on(ResponseProceed::class, function (ResponseProceed $event) use ($debug): void {
            $response = $event->getResponse();

            if ($response && $debug) {
                $this->onResponse($response, $debug);
            }
        }, -100);
    }

    /**
     * @param ResponseInterface $response
     * @param bool $debug
     */
    private function onResponse(ResponseInterface $response, bool $debug): void
    {
        $response->debug($debug);

        $response->addExtension('memory', [
            'current' => \number_format(\memory_get_usage() / 1024, 2) . 'Kb',
            'peak'    => \number_format(\memory_get_peak_usage() / 1024, 2) . 'Kb',
        ]);

        foreach ($response->getExceptions() as $exception) {
            if ($exception instanceof GraphQLException) {
                $exception->publish();
                $exception->addExtension(new DebugHttpExtension($exception));
            }
        }
    }
}
