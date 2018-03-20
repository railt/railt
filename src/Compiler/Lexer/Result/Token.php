<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Result;

use Railt\Compiler\Lexer\TokenInterface;

/**
 * Class Token
 */
class Token implements TokenInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private $context = [];

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var int|null
     */
    private $length;

    /**
     * @var int|null
     */
    private $bytes;

    /**
     * Token constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @param array $context
     * @return Token
     */
    public function with(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param int $offset
     * @return Token
     */
    public function at(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return string
     */
    public function value(int $offset = null): string
    {
        if ($offset === null) {
            return $this->value;
        }

        return $this->context[$offset] ?? '';
    }

    /**
     * @return int
     */
    public function bytes(): int
    {
        if ($this->bytes === null) {
            $this->bytes = \strlen($this->value);
        }

        return $this->bytes;
    }

    /**
     * @return int
     */
    public function length(): int
    {
        if ($this->length === null) {
            $this->length = \mb_strlen($this->value);
        }

        return $this->length;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $format = function (string $value) {
            $value = (string)(\preg_replace('/\s+/iu', ' ', $value) ?? $value);
            $value = \addcslashes($value, '"');

            if (\mb_strlen($value) > 35) {
                $value = \mb_substr($value, 0, 30) .
                    \sprintf('… (%s+)', \mb_strlen($value) - 30);
            }

            return $value;
        };

        return \sprintf('"%s" (%s)', $format($this->value()), $this->name());
    }

    /**
     * @param string[] ...$names
     * @return bool
     */
    public function is(string ...$names): bool
    {
        return \in_array($this->name, $names, true);
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return false;
    }
}
