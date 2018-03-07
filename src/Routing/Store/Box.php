<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Store;
use Railt\Http\InputInterface;

/**
 * Class Box
 */
final class Box implements \ArrayAccess
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array
     */
    private $serialized;

    /**
     * Box constructor.
     * @param $data
     * @param array $serialized
     */
    public function __construct($data, array $serialized)
    {
        $this->data = $data;
        $this->serialized = $serialized;
    }

    /**
     * @param array $items
     * @return Box
     */
    public static function restruct(array $items): Box
    {
        $data = [];
        $serialized = [];

        /** @var Box $box */
        foreach ($items as $box) {
            $data[] = $box->getValue();
            $serialized[] = $box->toArray();
        }

        return new static($data, $serialized);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->serialized;
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
