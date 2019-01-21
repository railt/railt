<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Rule;

/**
 * Class Terminal
 */
class Terminal extends Rule
{
    /**
     * Token name.
     * @var string
     */
    protected $tokenName;

    /**
     * Token value.
     * @var string
     */
    protected $value;

    /**
     * Whether the token is kept or not in the AST.
     * @var bool
     */
    protected $kept = false;

    /**
     * Token constructor.
     *
     * @param string|int $name Name.
     * @param string $tokenName Token name.
     * @param bool $kept Whether the token is kept or not in the AST.
     */
    public function __construct($name, string $tokenName, bool $kept = false)
    {
        parent::__construct($name, null);

        $this->tokenName = $tokenName;
        $this->kept = $kept;
    }

    /**
     * Get token name.
     *
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * Check whether the token is kept in the AST or not.
     * @return bool
     */
    public function isKept(): bool
    {
        return $this->kept;
    }
}
