<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Location;

use Ramsey\Collection\Set;

/**
 * Class LocationsCollection
 */
final class LocationsCollection extends Set implements \JsonSerializable
{
    /**
     * Collection constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct(LocationInterface::class, []);

        foreach ($data as $index => $element) {
            $this->offsetSet($index, $element);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        parent::offsetSet($offset, $this->transform($value));
    }

    /**
     * @param mixed $element
     * @return LocationInterface|mixed
     */
    private function transform($element)
    {
        if (\is_array($element)) {
            return new Location(...\array_values($element));
        }

        return $element;
    }

    /**
     * {@inheritDoc}
     */
    public function add($element): bool
    {
        return parent::add($this->transform($element));
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
