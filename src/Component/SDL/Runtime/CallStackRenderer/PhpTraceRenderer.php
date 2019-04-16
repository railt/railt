<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Runtime\CallStackRenderer;

/**
 * Class PhpTraceRenderer
 */
class PhpTraceRenderer extends BaseTraceRenderer
{
    /**
     * @var array
     */
    private $trace;

    /**
     * PhpTraceRenderer constructor.
     * @param array $trace
     */
    public function __construct(array $trace)
    {
        $this->trace = $trace;

        $this->file = (string)($trace['file'] ?? 'php://input');
        $this->line = (int)($trace['line'] ?? 0);
    }

    /**
     * @param int $position
     * @return string
     */
    public function toTraceString(int $position): string
    {
        return \vsprintf('#%d %s(%d): %s', [
            $position,
            $this->getFile(),
            $this->getLine(),
            $this->formatTrace($this->trace),
        ]);
    }

    /**
     * @param array $trace
     * @return string
     */
    private function formatTrace(array $trace): string
    {
        $result = $trace['function'];

        if (\array_key_exists('class', $trace)) {
            $result = $trace['class'] . $trace['type'] . $result;
        }

        $arguments = [];

        foreach ((array)($trace['args'] ?? []) as $argument) {
            $arguments[] = $this->valueToString($argument);
        }

        return $result . '(' . \implode(', ', $arguments) . ')';
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function valueToString($value, int $depth = 1): string
    {
        if (\is_iterable($value)) {
            return $this->iteratorToString($value, $depth);
        }

        if (\is_object($value)) {
            return $this->objectToString($value);
        }

        return $this->scalarToString($value);
    }

    /**
     * @param \Traversable|array $iterator
     * @param int $depth
     * @return string
     */
    private function iteratorToString($iterator, int $depth): string
    {
        $parts = [];

        foreach ($iterator as $i => $item) {
            if ($i >= 2 || $depth <= 0) {
                $parts[] = '...';
                break;
            }

            $parts[] = $this->valueToString($item, $depth - 1);
        }

        $body = \implode(', ', $parts);

        if (\is_object($iterator)) {
            return $this->objectToString($iterator) . '<' . $body . '>';
        }

        return '[' . $body . ']';
    }

    /**
     * @param int|float|string|int $scalar
     * @return string
     */
    private function scalarToString($scalar): string
    {
        return \print_r($scalar, true);
    }

    /**
     * @param object $object
     * @return string
     */
    private function objectToString($object): string
    {
        return \get_class($object);
    }
}
