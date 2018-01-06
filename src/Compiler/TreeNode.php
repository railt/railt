<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;

/**
 * Class \Railt\Compiler\TreeNode.
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
    protected $id;

    /**
     * Value of the node (non-null for token nodes).
     *
     * @var array
     */
    protected $value;

    /**
     * Children.
     *
     * @var array
     */
    protected $children;

    /**
     * Parent.
     *
     * @var \Railt\Compiler\TreeNode
     */
    protected $parent;

    /**
     * Attached data.
     *
     * @var array
     */
    protected $data     = [];

    /**
     * Constructor.
     *
     * @param string $id ID.
     * @param array $value Value.
     * @param array $children Children.
     * @param \Railt\Compiler\TreeNode $parent Parent.
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
     * @return  string
     */
    public function setId($id)
    {
        $old       = $this->id;
        $this->id  = $id;

        return $old;
    }

    /**
     * Get ID.
     *
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value.
     *
     * @param array $value Value (token & value).
     * @return  array
     */
    public function setValue(array $value)
    {
        $old          = $this->value;
        $this->value  = $value;

        return $old;
    }

    /**
     * Get value.
     *
     * @return  array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get value token.
     *
     * @return  string
     */
    public function getValueToken()
    {
        return
            isset($this->value['token'])
                ? $this->value['token']
                : null;
    }

    /**
     * Get value value.
     *
     * @return  string
     */
    public function getValueValue()
    {
        return
            isset($this->value['value'])
                ? $this->value['value']
                : null;
    }

    /**
     * Get token offset.
     *
     * @return  int
     */
    public function getOffset()
    {
        return
            isset($this->value['offset'])
                ? $this->value['offset']
                : 0;
    }

    /**
     * Check if the node represents a token or not.
     *
     * @return  bool
     */
    public function isToken()
    {
        return ! empty($this->value);
    }

    /**
     * Prepend a child.
     *
     * @param \Railt\Compiler\TreeNode $child Child.
     * @return  \Railt\Compiler\TreeNode
     */
    public function prependChild(self $child)
    {
        \array_unshift($this->children, $child);

        return $this;
    }

    /**
     * Append a child.
     *
     * @param \Railt\Compiler\TreeNode $child Child.
     * @return  \Railt\Compiler\TreeNode
     */
    public function appendChild(self $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Set children.
     *
     * @param array $children Children.
     * @return  array
     */
    public function setChildren(array $children)
    {
        $old             = $this->children;
        $this->children  = $children;

        return $old;
    }

    /**
     * Get child.
     *
     * @param int $i Index.
     * @return  \Railt\Compiler\TreeNode
     */
    public function getChild($i)
    {
        return
            true === $this->childExists($i)
                ? $this->children[$i]
                : null;
    }

    /**
     * Get children.
     *
     * @return  array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get number of children.
     *
     * @return  int
     */
    public function getChildrenNumber()
    {
        return \count($this->children);
    }

    /**
     * Check if a child exists.
     *
     * @param int $i Index.
     * @return  bool
     */
    public function childExists($i)
    {
        return \array_key_exists($i, $this->children);
    }

    /**
     * Set parent.
     *
     * @param \Railt\Compiler\TreeNode $parent Parent.
     * @return  \Railt\Compiler\TreeNode
     */
    public function setParent(self $parent)
    {
        $old           = $this->parent;
        $this->parent  = $parent;

        return $old;
    }

    /**
     * Get parent.
     *
     * @return  \Railt\Compiler\TreeNode
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get data.
     *
     * @return  array
     */
    public function &getData()
    {
        return $this->data;
    }

    /**
     * Accept a visitor.
     *
     * @param Visit $visitor Visitor.
     * @param mixed &$handle Handle (reference).
     * @param mixed $eldnah Handle (no reference).
     * @return  mixed
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
     * @return  void
     */
    public function __destruct()
    {
        unset($this->parent);
    }
}
