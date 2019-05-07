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
 * Class ObjectItem
 */
class ObjectItem extends FunctionItem implements ObjectItemInterface, MutableObjectItemInterface
{
    /**
     * @var string
     */
    public const FIELD_CLASS = 'class';

    /**
     * @var string
     */
    public const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    public const TYPE_CALL = '->';

    /**
     * @var string
     */
    public const TYPE_CALL_STATIC = '::';

    /**
     * @var string
     */
    protected const TEMPLATE_DEFAULT = '%s: %s%s%s(%s)';

    /**
     * @var string
     */
    protected $class = \stdClass::class;

    /**
     * @var string
     */
    protected $type = self::TYPE_CALL;

    /**
     * @param array $trace
     * @return ObjectItemInterface|$this
     */
    public static function fromArray(array $trace): ItemInterface
    {
        \assert(\array_key_exists(static::FIELD_CLASS, $trace));
        \assert(\array_key_exists(static::FIELD_TYPE, $trace));

        /** @var MutableObjectItemInterface|ObjectItemInterface $instance */
        $instance = parent::fromArray($trace);
        $instance->withClass($trace[static::FIELD_CLASS]);
        $instance->withStaticCall($trace[static::FIELD_TYPE] === static::TYPE_CALL_STATIC);

        return $instance;
    }

    /**
     * @return bool
     */
    public function isStaticCall(): bool
    {
        return $this->type === static::TYPE_CALL_STATIC;
    }

    /**
     * @param bool $static
     * @return MutableObjectItemInterface|$this
     */
    public function withStaticCall(bool $static = true): MutableObjectItemInterface
    {
        $this->type = $static ? static::TYPE_CALL_STATIC : static::TYPE_CALL;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return MutableObjectItemInterface|$this
     */
    public function withClass(string $class): MutableObjectItemInterface
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            static::FIELD_CLASS => $this->getClass(),
            static::FIELD_TYPE  => $this->getType(),
        ]);
    }

    /**
     * @return string
     */
    private function getType(): string
    {
        return $this->isStaticCall() ? static::TYPE_CALL_STATIC : static::TYPE_CALL;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return \vsprintf(static::TEMPLATE_DEFAULT, [
            $this->fileToString(),
            $this->getClass(),
            $this->getType(),
            $this->getFunction(),
            $this->getArgumentsAsString(),
        ]);
    }
}
