<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

/**
 * Class Definition
 */
class Definition
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $skip = false;

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
     * @return bool
     */
    public function isSkipped(): bool
    {
        return $this->skip;
    }

    /**
     * @return Definition
     */
    public function skip(): self
    {
        $this->skip = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
