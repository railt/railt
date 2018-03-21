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
 * Class Token
 */
class Terminal extends Rule
{
    /**
     * Token name.
     *
     * @var string
     */
    protected $tokenName;

    /**
     * Token value.
     *
     * @var string
     */
    protected $value;

    /**
     * Whether the token is kept or not in the AST.
     *
     * @var bool
     */
    protected $kept = false;

    /**
     * Token offset.
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * Constructor.
     *
     * @param string $name Name.
     * @param string $tokenName Token name.
     * @param string $nodeId Node ID.
     * @param bool $kept Whether the token is kept or not in the AST.
     */
    public function __construct($name, $tokenName, $nodeId, bool $kept = false)
    {
        parent::__construct($name, null, $nodeId);

        $this->tokenName   = $tokenName;
        $this->setKept($kept);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->name,
            $this->tokenName,
            $this->nodeId,
            $this->kept,
        ];
    }

    /**
     * Get token name.
     *
     * @return  string
     */
    public function getTokenName()
    {
        return $this->tokenName;
    }

    /**
     * Get token value.
     *
     * @return  string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set token value.
     *
     * @param string $value Value.
     * @return  string
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get token offset.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param $offset
     */
    public function setOffset($offset): void
    {
        $this->offset = $offset;
    }

    /**
     * Check whether the token is kept in the AST or not.
     * @return  bool
     */
    public function isKept()
    {
        return $this->kept;
    }

    /**
     * @param $kept
     */
    public function setKept($kept): void
    {
        $this->kept = $kept;
    }
}
