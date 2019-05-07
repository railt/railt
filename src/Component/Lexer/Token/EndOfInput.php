<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Lexer\Token;

/**
 * Class Eoi
 */
final class EndOfInput extends BaseToken
{
    /**
     * End of input token name
     */
    public const T_NAME = 'T_EOI';

    /**
     * @var int
     */
    private $offset;

    /**
     * Eoi constructor.
     *
     * @param int $offset
     */
    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::T_NAME;
    }

    /**
     * @param int|null $offset
     * @return string
     */
    public function getValue(int $offset = null): string
    {
        return "\0";
    }

    /**
     * @return iterable|string[]
     */
    public function getGroups(): iterable
    {
        return [$this->getValue()];
    }

    /**
     * @return int
     */
    public function getBytes(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return 0;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \sprintf('"%s" (%s)', '\0', self::T_NAME);
    }
}
