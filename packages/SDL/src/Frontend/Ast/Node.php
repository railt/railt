<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast;

use Phplrt\Contracts\Ast\NodeInterface;

/**
 * Class Node
 *
 * @property-read string $kind
 */
abstract class Node implements NodeInterface, \JsonSerializable
{
    use ResolvableTrait;

    /**
     * @var Location|null
     */
    public ?Location $loc = null;

    /**
     * @return \Traversable|NodeInterface[]
     */
    public function getIterator(): \Traversable
    {
        foreach (\get_object_vars($this) as $property => $value) {
            if ($value instanceof NodeInterface || \is_array($value)) {
                yield $property => $value;
            }
        }
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->loc->start;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return (string)\json_encode($this->jsonSerialize(), \JSON_PRETTY_PRINT | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return (string)\var_export($this, true);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $properties = \array_merge(['kind' => $this->getKind()], \get_object_vars($this));

        if (isset($properties['loc'])) {
            unset($properties['loc']);
        }

        return $properties;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        $fqn = \str_replace('\\', \DIRECTORY_SEPARATOR, static::class);

        return \basename($fqn, 'Node');
    }
}
