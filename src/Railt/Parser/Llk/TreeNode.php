<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Llk;

use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;

/**
 * Class \Railt\Parser\Llk\TreeNode.
 *
 * Provide a generic node for the AST produced by LL(k) parser.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class TreeNode implements Element
{
    /**
     * ID (should be something like #ruleName or token).
     *
     * @var string
     */
    protected $_id;

    /**
     * Value of the node (non-null for token nodes).
     *
     * @var array
     */
    protected $_value;

    /**
     * Children.
     *
     * @var array
     */
    protected $_children;

    /**
     * Parent.
     *
     * @var \Railt\Parser\Llk\TreeNode
     */
    protected $_parent;

    /**
     * Attached data.
     *
     * @var array
     */
    protected $_data     = [];

    /**
     * Constructor.
     *
     * @param string $id ID.
     * @param array $value Value.
     * @param array $children Children.
     * @param \Railt\Parser\Llk\TreeNode $parent Parent.
     */
    public function __construct(
        $id,
        array $value    = null,
        array $children = [],
        self  $parent   = null
    ) {
        $this->setId($id);

        if (! empty($value)) {
            $this->setValue($value);
        }

        $this->setChildren($children);

        if (null !== $parent) {
            $this->setParent($parent);
        }
    }

    /**
     * Set ID.
     *
     * @param string $id ID.
     * @return string
     */
    public function setId($id)
    {
        $old       = $this->_id;
        $this->_id = $id;

        return $old;
    }

    /**
     * Get ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set value.
     *
     * @param array $value Value (token & value).
     * @return array
     */
    public function setValue(array $value)
    {
        $old          = $this->_value;
        $this->_value = $value;

        return $old;
    }

    /**
     * Get value.
     *
     * @return array
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Get value token.
     *
     * @return string
     */
    public function getValueToken()
    {
        return
            isset($this->_value['token'])
                ? $this->_value['token']
                : null;
    }

    /**
     * Get value value.
     *
     * @return string
     */
    public function getValueValue()
    {
        return
            isset($this->_value['value'])
                ? $this->_value['value']
                : null;
    }

    /**
     * Get token offset.
     *
     * @return int
     */
    public function getOffset()
    {
        return
            isset($this->_value['offset'])
                ? $this->_value['offset']
                : 0;
    }

    /**
     * Check if the node represents a token or not.
     *
     * @return bool
     */
    public function isToken()
    {
        return ! empty($this->_value);
    }

    /**
     * Prepend a child.
     *
     * @param \Railt\Parser\Llk\TreeNode $child Child.
     * @return \Railt\Parser\Llk\TreeNode
     */
    public function prependChild(self $child)
    {
        \array_unshift($this->_children, $child);

        return $this;
    }

    /**
     * Append a child.
     *
     * @param \Railt\Parser\Llk\TreeNode $child Child.
     * @return \Railt\Parser\Llk\TreeNode
     */
    public function appendChild(self $child)
    {
        $this->_children[] = $child;

        return $this;
    }

    /**
     * Set children.
     *
     * @param array $children Children.
     * @return array
     */
    public function setChildren(array $children)
    {
        $old             = $this->_children;
        $this->_children = $children;

        return $old;
    }

    /**
     * Get child.
     *
     * @param int $i Index.
     * @return \Railt\Parser\Llk\TreeNode
     */
    public function getChild($i)
    {
        return
            true === $this->childExists($i)
                ? $this->_children[$i]
                : null;
    }

    /**
     * Get children.
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * Get number of children.
     *
     * @return int
     */
    public function getChildrenNumber()
    {
        return \count($this->_children);
    }

    /**
     * Check if a child exists.
     *
     * @param int $i Index.
     * @return bool
     */
    public function childExists($i)
    {
        return \array_key_exists($i, $this->_children);
    }

    /**
     * Set parent.
     *
     * @param \Railt\Parser\Llk\TreeNode $parent Parent.
     * @return \Railt\Parser\Llk\TreeNode
     */
    public function setParent(self $parent)
    {
        $old           = $this->_parent;
        $this->_parent = $parent;

        return $old;
    }

    /**
     * Get parent.
     *
     * @return \Railt\Parser\Llk\TreeNode
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function &getData()
    {
        return $this->_data;
    }

    /**
     * Accept a visitor.
     *
     * @param \Hoa\Visitor\Visit $visitor Visitor.
     * @param mixed &$handle Handle (reference).
     * @param mixed $eldnah Handle (no reference).
     * @return mixed
     */
    public function accept(
        Visit $visitor,
        &$handle = null,
        $eldnah  = null
    ) {
        return $visitor->visit($this, $handle, $eldnah);
    }

    /**
     * Remove circular reference to the parent (help the garbage collector).
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->_parent);
    }
}
