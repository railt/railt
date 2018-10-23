<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Rule;

/**
 * Class BaseSymbol
 */
abstract class BaseSymbol implements Symbol
{
    /**
     * @var string|int
     */
    protected $id;

    /**
     * @var bool
     */
    protected $kept;

    /**
     * BaseSymbol constructor.
     * @param string|int $id
     * @param bool $kept
     */
    public function __construct($id, bool $kept = false)
    {
        $this->id   = $id;
        $this->kept = $kept;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isKept(): bool
    {
        return $this->kept;
    }
}
