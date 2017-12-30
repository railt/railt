<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Llk\Rule;

/**
 * Class \Railt\Parser\Llk\Rule\Token.
 *
 * The token rule.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class Token extends Rule
{
    /**
     * Token name.
     *
     * @var string
     */
    protected $_tokenName;

    /**
     * Namespace.
     *
     * @var string
     */
    protected $_namespace;

    /**
     * Token representation.
     *
     * @var string
     */
    protected $_regex;

    /**
     * AST of the regex.
     *
     * @var \Railt\Parser\Llk\TreeNode
     */
    protected $_ast;

    /**
     * Token value.
     *
     * @var string
     */
    protected $_value;

    /**
     * Whether the token is kept or not in the AST.
     *
     * @var bool
     */
    protected $_kept = false;

    /**
     * Unification index.
     *
     * @var int
     */
    protected $_unification = -1;

    /**
     * Token offset.
     *
     * @var int
     */
    protected $_offset = 0;

    /**
     * Constructor.
     *
     * @param string $name Name.
     * @param string $tokenName Token name.
     * @param string $nodeId Node ID.
     * @param int $unification Unification index.
     * @param bool $kept Whether the token is kept or not in the AST.
     */
    public function __construct(
        $name,
        $tokenName,
        $nodeId,
        $unification,
        $kept = false
    ) {
        parent::__construct($name, null, $nodeId);

        $this->_tokenName   = $tokenName;
        $this->_unification = $unification;
        $this->setKept($kept);
    }

    /**
     * Get token name.
     *
     * @return  string
     */
    public function getTokenName()
    {
        return $this->_tokenName;
    }

    /**
     * Get token namespace.
     *
     * @return  string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Set token namespace.
     *
     * @param string $namespace Namespace.
     * @return  string
     */
    public function setNamespace($namespace)
    {
        $old              = $this->_namespace;
        $this->_namespace = $namespace;

        return $old;
    }

    /**
     * Set representation.
     *
     * @param string $regex Representation.
     * @return  string
     */
    public function setRepresentation($regex)
    {
        $old          = $this->_regex;
        $this->_regex = $regex;

        return $old;
    }

    /**
     * Get token value.
     *
     * @return  string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Set token value.
     *
     * @param string $value Value.
     * @return  string
     */
    public function setValue($value)
    {
        $old          = $this->_value;
        $this->_value = $value;

        return $old;
    }

    /**
     * Get token offset.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Set token offset.
     *
     * @param int $offset Offset.
     * @return  int
     */
    public function setOffset($offset)
    {
        $old           = $this->_offset;
        $this->_offset = $offset;

        return $old;
    }

    /**
     * Check whether the token is kept in the AST or not.
     *
     * @return  bool
     */
    public function isKept()
    {
        return $this->_kept;
    }

    /**
     * Set whether the token is kept or not in the AST.
     *
     * @param bool $kept Kept.
     * @return  bool
     */
    public function setKept($kept)
    {
        $old         = $this->_kept;
        $this->_kept = $kept;

        return $old;
    }

    /**
     * Get unification index.
     *
     * @return  int
     */
    public function getUnificationIndex()
    {
        return $this->_unification;
    }
}
