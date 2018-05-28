<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Events;

use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TypeBuilding
 */
class TypeBuilding extends Event
{
    /**
     * @var TypeDefinition
     */
    private $type;

    /**
     * TypeBuilding constructor.
     * @param TypeDefinition $type
     */
    public function __construct(TypeDefinition $type)
    {
        $this->type = $type;
    }

    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition
    {
        return $this->type;
    }

    /**
     * @param EventDispatcherInterface $events
     * @param TypeDefinition $type
     * @return bool
     */
    public static function canBuild(EventDispatcherInterface $events, TypeDefinition $type): bool
    {
        $event = $events->dispatch(self::class, new static($type));

        return ! $event->isPropagationStopped();
    }
}
