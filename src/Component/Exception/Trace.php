<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception;

use Railt\Component\Exception\Trace\FunctionItem;
use Railt\Component\Exception\Trace\ItemInterface;
use Railt\Component\Exception\Trace\ObjectItem;

/**
 * Class Trace
 */
class Trace implements TraceInterface, MutableTraceInterface
{
    /**
     * @var string
     */
    private const TEMPLATE_TRACE = '#%d %s';

    /**
     * @var string
     */
    private const TEMPLATE_MAIN = '{main}';

    /**
     * @var array|ItemInterface[]
     */
    private $trace;

    /**
     * Trace constructor.
     *
     * @param array $trace
     */
    public function __construct(array $trace)
    {
        \assert(\count($trace) > 0);

        $this->trace = \array_map([$this, 'map'], $trace);
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->trace as $item) {
            $result[] = $item->toArray();
        }

        return $result;
    }

    /**
     * @return \Traversable|ItemInterface[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->trace);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $result = [];

        foreach ($this->trace as $index => $item) {
            $result[] = \sprintf(static::TEMPLATE_TRACE, $index, $item->toString());
        }

        $result[] = \sprintf(static::TEMPLATE_TRACE, \count($result), self::TEMPLATE_MAIN);

        return \implode(\PHP_EOL, $result);
    }

    /**
     * @param ItemInterface $item
     * @return ItemInterface|$this
     */
    public function withTrace(ItemInterface $item): ItemInterface
    {
        \array_unshift($this->trace, $item);

        return $this;
    }

    /**
     * @param array $item
     * @return ItemInterface
     */
    private function map(array $item): ItemInterface
    {
        return isset($item[ObjectItem::FIELD_CLASS]) ? ObjectItem::fromArray($item) : FunctionItem::fromArray($item);
    }
}
