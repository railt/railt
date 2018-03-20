<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Rule;

use Railt\Compiler\Parser\Ast\NodeInterface;

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
     * Token representation.
     *
     * @var string
     */
    protected $regex;

    /**
     * AST of the regex.
     *
     * @var NodeInterface
     */
    protected $ast;

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
     * Unification index.
     *
     * @var int
     */
    protected $unification = -1;

    /**
     * Token offset.
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * Constructor.
     *
     * @param string|int $index Name.
     * @param string $name Token name.
     * @param string $nodeId Node ID.
     * @param int $unification Unification index.
     * @param bool $kept Whether the token is kept or not in the AST.
     */
    public function __construct($index, string $name, $nodeId, $unification, $kept = false)
    {
        parent::__construct($index, null, $nodeId);

        $this->tokenName   = $name;
        $this->unification = $unification;
        $this->setKept($kept);
    }

    /**
     * @return array
     */
    public function args(): array
    {
        return [
            $this->name,
            $this->tokenName,
            $this->nodeId,
            $this->unification,
            $this->kept,
        ];
    }

    /**
     * Get token name.
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * Get token value.
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set token value.
     * @param string $value
     * @return self|$this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get token offset.
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Set token offset.
     * @param int $offset
     * @return self|$this
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Check whether the token is kept in the AST or not.
     * @return bool
     */
    public function isKept(): bool
    {
        return $this->kept;
    }

    /**
     * Set whether the token is kept or not in the AST.
     * @param bool $kept Kept.
     * @return self|$this
     */
    public function setKept(bool $kept): self
    {
        $this->kept = $kept;

        return $this;
    }

    /**
     * Get unification index.
     * @return int
     */
    public function getUnificationIndex(): int
    {
        return $this->unification;
    }
}
