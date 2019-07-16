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
use Illuminate\Support\Arr;
use Railt\Dumper\TypeDumper;
use Railt\Container\Container;
use Clockwork\Request\UserData;
use Railt\Http\RequestInterface;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtRequestSubscriber
 */
class RailtRequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var Clockwork
     */
    private $clockwork;

    /**
     * RailtRequestSubscriber constructor.
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
            RequestReceived::class => ['onRequest', 100]
        ];
    }

    /**
     * @param RequestReceived $event
     */
    public function onRequest(RequestReceived $event): void
    {
        $request = $event->getRequest();

        /** @var UserData $context */
        $context = $this->clockwork
            ->userData('railt-request-' . $request->getId())
            ->title('Request');

        $context->counters([
            'Connection ID' => $event->getConnection()->getId(),
            'Request ID'    => $request->getId(),
            'Variables'     => \count($request->getVariables(), \COUNT_RECURSIVE),
        ]);

        $context->table('', [
            ['Description' => 'ID', 'Value' => $request->getId()],
            ['Description' => 'Type', 'Value' => $request->getQueryType()],
            ['Description' => 'Query', 'Value' => $request->getQuery()],
            ['Description' => 'Operation', 'Value' => $request->getOperation()],
        ]);

        $this->shareVariables($request, $context);
    }

    /**
     * @param RequestInterface $request
     * @param UserData $context
     */
    private function shareVariables(RequestInterface $request, UserData $context): void
    {
        $variables = [];

        foreach (Arr::dot($request->getVariables()) as $key => $value) {
            $value = \is_scalar($value) ? $value : TypeDumper::render($value);

            $variables[] = ['Name' => '$' . $key, 'Value' => $value];
        }

        if (\count($variables)) {
            $context->table('Variables', $variables);
        }
    }
}
