<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\TokenInterface;

/**
 * Class Token
 */
final class Token implements TokenInterface
{
    public const T_EOF = -0x01;
    public const T_EOF_NAME = 'EOF';

    /**@#+
     * A default token channels.
     */
    public const CHANNEL_DEFAULT = 'default';
    public const CHANNEL_SKIP    = 'skip';
    public const CHANNEL_SYSTEM  = 'system';
    /**@#-*/

    /**
     * @var string|int
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string|null
     */
    private $channel;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $context = [];

    /**
     * @var int
     */
    private $bytes;

    /**
     * @var int
     */
    private $length;

    /***
     * Token constructor.
     * @param string|int $name
     * @param string $value
     */
    public function __construct($name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @param int $offset
     * @return Token
     */
    public static function eof(int $offset): Token
    {
        return (new static(self::T_EOF_NAME, "\0"))
            ->in(Token::CHANNEL_SYSTEM)
            ->at($offset);
    }

    /**
     * @param int $offset
     * @return Token
     */
    public function at(int $offset): Token
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param null|string $channel
     * @return Token
     */
    public function in(?string $channel): Token
    {
        $this->channel = $channel;

        return $this;
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
     * @return int|string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function channel(): string
    {
        return $this->channel ?? Definition::CHANNEL_DEFAULT;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param int $offset
     * @return string|null
     */
    public function get(int $offset): ?string
    {
        return $this->context[$offset] ?? null;
    }

    /**
     * @param array $context
     * @return Token
     */
    public function with(array $context): Token
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return $this->name === self::T_EOF_NAME;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->channel === self::CHANNEL_SYSTEM;
    }

    /**
     * @return bool
     */
    public function isSkipped(): bool
    {
        return $this->channel === self::CHANNEL_SKIP;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $value = \trim(\preg_replace('/\s+/iu', ' ', $this->value));
        $value = \addcslashes($value, '"');

        return \sprintf('"%s" (%s)', $value, $this->name);
    }

    /**
     * @param array ...$names
     * @return bool
     */
    public function is(...$names): bool
    {
        return \in_array($this->name, $names, true);
    }
}
