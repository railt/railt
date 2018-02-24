<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Context;

use Railt\Compiler\Generator\Grammar\Lexer;
use Railt\Compiler\Lexer\Tokens\Output;
use Railt\Io\Position;

/**
 * Class Item
 */
final class Item implements \ArrayAccess
{
    /**
     * @var array
     */
    private $rule;

    /**
     * @var Builder
     */
    private $ctx;

    /**
     * @var null|Item
     */
    private $prev;

    /**
     * InputRule constructor.
     * @param array $rule
     * @param Builder $ctx
     * @param Item|null $prev
     */
    public function __construct(array $rule, Builder $ctx, ?self $prev)
    {
        $this->rule = $rule;
        $this->ctx  = $ctx;
        $this->prev = $prev;
    }

    /**
     * @return null|Item
     */
    public function previous(): ?self
    {
        return $this->prev;
    }

    /**
     * Is the current rule is a:
     * 1) Kept token    - <TOKEN>
     * 2) Skipped token - ::TOKEN::
     * 3) Rule link     - Rule()
     *
     * @return bool
     */
    public function isConcatenable(): bool
    {
        return \in_array($this->rule[Output::T_NAME], [
            Lexer::T_KEPT,
            Lexer::T_SKIPPED,
            Lexer::T_NAMED,
        ], true);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $offset >= 0 && $offset <= 5;
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->rule[$offset] ?? null;
    }

    /**
     * @param int $offset
     * @param mixed $value
     * @throws \LogicException
     */
    public function offsetSet($offset, $value): void
    {
        throw new \LogicException('Can not change immutable data structure');
    }

    /**
     * @param int $offset
     * @throws \LogicException
     */
    public function offsetUnset($offset): void
    {
        throw new \LogicException('Can not change immutable data structure');
    }

    /**
     * @param int|null $index
     * @return string|array
     */
    public function context(int $index = null)
    {
        $ctx = $this->rule[Output::T_CONTEXT];

        return $index === null ? $ctx : ($ctx[$index] ?? null);
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return $this->rule[Output::T_LENGTH];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function is(int $id): bool
    {
        return $this->id() === $id;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->rule[Output::T_NAME];
    }

    /**
     * @return Position
     */
    public function position(): Position
    {
        return $this->ctx->getFile()->getPosition($this->offset());
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->rule[Output::T_OFFSET];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value();
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->rule[Output::T_VALUE];
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return ['name' => $this->name(), 'value' => $this->value()];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return Lexer::getTokenName($this->id());
    }
}
