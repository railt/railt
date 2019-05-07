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
 * Class Token
 */
class Token extends BaseToken
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $value;

    /**
     * @var int
     */
    private $offset;

    /**
     * Token constructor.
     *
     * @param string $name
     * @param string|array $value
     * @param int $offset
     */
    public function __construct(string $name, $value, int $offset = 0)
    {
        $this->name = $name;
        $this->value = (array)$value;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int|null $offset
     * @return string|null
     */
    public function getValue(int $offset = 0): ?string
    {
        return $this->value[$offset] ?? null;
    }

    /**
     * @return iterable|string[]
     */
    public function getGroups(): iterable
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
