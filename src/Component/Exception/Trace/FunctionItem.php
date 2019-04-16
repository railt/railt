<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception\Trace;

/**
 * Class FnItem
 */
class FunctionItem extends Item implements FunctionItemInterface, MutableFunctionItemInterface
{
    /**
     * @var string
     */
    public const FIELD_FUNCTION = 'function';

    /**
     * @var string
     */
    public const FIELD_ARGS = 'args';

    /**
     * @var string
     */
    protected const UNKNOWN_FUNCTION_NAME = 'unknown';

    /**
     * @var string
     */
    protected const TEMPLATE_UNKNOWN = '%s: %s';

    /**
     * @var string
     */
    protected const TEMPLATE_DEFAULT = '%s: %s(%s)';

    /**
     * @var string
     */
    protected $function = self::UNKNOWN_FUNCTION_NAME;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * @param array $trace
     * @return FunctionItemInterface|$this
     */
    public static function fromArray(array $trace): ItemInterface
    {
        \assert(\array_key_exists(static::FIELD_FUNCTION, $trace));

        /** @var MutableFunctionItemInterface|FunctionItemInterface $instance */
        $instance = parent::fromArray($trace);
        $instance->withFunction($trace[static::FIELD_FUNCTION]);
        $instance->withArguments(...$trace[static::FIELD_ARGS] ?? []);

        return $instance;
    }

    /**
     * @param string $function
     * @return MutableFunctionItemInterface|$this
     */
    public function withFunction(string $function): MutableFunctionItemInterface
    {
        $this->function = $function;

        return $this;
    }

    /**
     * @param mixed ...$values
     * @return MutableFunctionItemInterface|$this
     */
    public function withArguments(...$values): MutableFunctionItemInterface
    {
        foreach ($values as $value) {
            $this->withArgument($value);
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @param int|null $index
     * @return MutableFunctionItemInterface|$this
     */
    public function withArgument($value, int $index = null): MutableFunctionItemInterface
    {
        if ($index === null) {
            $this->args[] = $value;
        } else {
            $this->args[$index] = $value;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            static::FIELD_FUNCTION => $this->getFunction(),
            static::FIELD_ARGS     => $this->getArguments(),
        ]);
    }

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->args;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $template = $this->getFunction() === static::UNKNOWN_FUNCTION_NAME
            ? static::TEMPLATE_UNKNOWN
            : static::TEMPLATE_DEFAULT;

        return \vsprintf($template, [
            $this->fileToString(),
            $this->getFunction(),
            $this->getArgumentsAsString()
        ]);
    }

    /**
     * @return string
     */
    protected function getArgumentsAsString(): string
    {
        return \implode(', ', \array_map([$this, 'renderArgument'], $this->getArguments()));
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function renderArgument($value): string
    {
        switch (true) {
            case \is_string($value):
                $body = \substr($value, 0, 15);

                return \sprintf('\'%s%s\'', $body, \strlen($value) > 15 ? '...' : '');

            case \is_int($value) || \is_float($value):
                return (string)$value;

            case \is_array($value):
                return 'Array';

            case \is_object($value):
                return \sprintf('Object(%s)', \get_class($value));

            case $value === null:
                return 'NULL';

            case \is_resource($value):
                return (string)$value;

            default:
                return \gettype($value);
        }
    }
}
