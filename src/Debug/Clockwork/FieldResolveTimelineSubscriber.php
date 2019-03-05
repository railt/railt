<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\Clockwork;

use Clockwork\Clockwork;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FieldResolveSubscriber
 */
class FieldResolveTimelineSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private const RAILT_PROCESS_FIELD = 'railt:resolve:%s';

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
            FieldResolve::class => [
                ['fieldResolving', 100],
                ['fieldResolved', -100],
            ]
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function fieldResolving(FieldResolve $event): void
    {
        $input = $event->getInput();

        $message = 'Resolving field "%s" of type %s';
        $message = \sprintf($message, $input->getPath(), \implode(', ', $input->getPreferTypes()));

        $this->clockwork->startEvent($this->fieldEventKey($event), $message);
    }

    /**
     * @param FieldResolve $event
     * @return string
     */
    private function fieldEventKey(FieldResolve $event): string
    {
        return \sprintf(self::RAILT_PROCESS_FIELD, $event->getPath());
    }

    /**
     * @param FieldResolve $event
     */
    public function fieldResolved(FieldResolve $event): void
    {
        $this->clockwork->endEvent($this->fieldEventKey($event));
    }
}
