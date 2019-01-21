<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Building;

use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BuildingEvent
 */
abstract class BuildingEvent extends Event implements BuildingEventInterface
{
    /**
     * @var TypeDefinition
     */
    private $definition;

    /**
     * BuildingEvent constructor.
     *
     * @param TypeDefinition $definition
     */
    public function __construct(TypeDefinition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @param TypeDefinition $definition
     * @return BuildingEvent|$this
     */
    public function withTypeDefinition(TypeDefinition $definition): self
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'type' => (string)$this->getTypeDefinition(),
        ];
    }

    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return $this->definition;
    }
}
