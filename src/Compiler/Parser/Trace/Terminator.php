<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Trace;

/**
 * Class Terminator
 */
class Terminator
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
     * @var int
     */
    private $offset;

    /**
     * @var bool
     */
    private $kept;

    /**
     * Terminator constructor.
     * @param string $name
     * @param string $value
     * @param int $offset
     * @param bool $kept
     */
    public function __construct(string $name, string $value, int $offset, bool $kept = true)
    {
        $this->name   = $name;
        $this->value  = $value;
        $this->offset = $offset;
        $this->kept   = $kept;
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

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function isKept(): bool
    {
        return $this->kept;
    }
}
