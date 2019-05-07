<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Subscribers;

use Railt\Http\Input;
use Railt\Http\InputInterface;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class InputParentSubscriber
 */
class InputParentSubscriber implements EventSubscriberInterface
{
    /**
     * @var InputSubscriber
     */
    private $inputs;

    /**
     * InputParentSubscriber constructor.
     *
     * @param InputSubscriber $inputs
     */
    public function __construct(InputSubscriber $inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FieldResolve::class => ['onFieldResolve', 100],
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function onFieldResolve(FieldResolve $event): void
    {
        $this->withParentResolver($event);
        $this->withParentInputResolver($event);
    }

    /**
     * @param FieldResolve $event
     */
    private function withParentResolver(FieldResolve $event): void
    {
        /** @var Input $input */
        $input = $event->getInput();
        $value = $event->getParentResult();

        $input->withParent(function (int $depth, InputInterface $input) use ($value) {
            if ($depth <= 0) {
                return $value;
            }

            $parent = $input->getParentInput($depth - 1);

            return $parent ? $parent->getParent(0) : null;
        });
    }

    /**
     * @param FieldResolve $event
     */
    private function withParentInputResolver(FieldResolve $event): void
    {
        $id = $event->getConnection()->getId();

        /** @var Input $input */
        $input = $event->getInput();

        $input->withParentInput(function (int $depth, InputInterface $input) use ($id) {
            [$chunks, $i] = [$input->getPathChunks(), 0];

            while ($chunks = \array_slice($chunks, 0, -1)) {
                if ($i++ >= $depth) {
                    return $this->inputs->getById($id, Input::chunksToPath($chunks));
                }
            }

            return null;
        });
    }
}
