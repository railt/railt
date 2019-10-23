<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\Contracts\Common\JsonableInterface;
use Railt\Contracts\Common\ArrayableInterface;
use Railt\Contracts\Common\RenderableInterface;

/**
 * Class Definition
 */
abstract class Definition implements
    ArrayableInterface,
    JsonableInterface,
    RenderableInterface
{
    /**
     * @var iterable|null
     */
    public ?iterable $ast = null;

    /**
     * Definition constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->fill($params);
    }

    /**
     * @param Definition $definition
     * @return Definition
     */
    public function mergeWith(self $definition): self
    {
        return $this->fillObject(clone $this, $definition->toArray());
    }

    /**
     * @param array $params
     * @return Definition
     */
    public function merge(array $params): self
    {
        return $this->fillObject(clone $this, $params);
    }

    /**
     * @param array $params
     * @return Definition|$this
     */
    public function fill(array $params): self
    {
        return $this->fillObject($this, $params);
    }

    /**
     * @param Definition $definition
     * @return Definition|$this
     */
    public function fillBy(self $definition): self
    {
        return $this->fillObject($this, $definition->toArray());
    }

    /**
     * @param Definition $context
     * @param array $params
     * @return Definition|$this
     */
    private function fillObject(self $context, array $params): self
    {
        foreach ($params as $name => $value) {
            $context->$name = $value;
        }

        return $context;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $map = fn ($value) => $value instanceof ArrayableInterface ? $value->toArray() : $value;

        return \array_map($map, \get_object_vars($this));
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return \json_encode($this, $options | \JSON_THROW_ON_ERROR);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->toJson(\JSON_PRETTY_PRINT);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        \assert((function () {
            foreach ((new \ReflectionObject($this))->getProperties() as $property) {
                if (! $property->isInitialized($this)) {
                    throw new \AssertionError(\vsprintf('Property %s should be initialized by %s', [
                        static::class . '::$' . $property->getName(),
                        $property->getType()->getName()
                    ]));
                }
            }

            return true;
        })->call($this));
    }
}
