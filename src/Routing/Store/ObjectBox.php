<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Store;

/**
 * Class Box
 */
final class ObjectBox extends BaseBox implements \ArrayAccess
{
    /**
     * Box constructor.
     * @param mixed $data
     * @param array $serialized
     */
    public function __construct($data, $serialized)
    {
        \assert(\is_array($serialized));

        // TODO Add $serialized validation

        parent::__construct($data, $serialized);
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return parent::getResponse();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->serialized);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->serialized[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->serialized[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->serialized[$offset]);
    }
}
