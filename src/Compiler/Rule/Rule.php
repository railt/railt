<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Rule;

/**
 * Class \Railt\Compiler\Rule.
 *
 * Rule parent.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
abstract class Rule
{
    /**
     * Rule name.
     *
     * @var string
     */
    protected $name;

    /**
     * Rule's children. Can be an array of names or a single name.
     *
     * @var mixed
     */
    protected $children;

    /**
     * Node ID.
     *
     * @var string
     */
    protected $nodeId;

    /**
     * Node options.
     *
     * @var array
     */
    protected $nodeOptions = [];

    /**
     * Default ID.
     *
     * @var string
     */
    protected $defaultId;

    /**
     * Default options.
     *
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * For non-transitional rule: PP representation.
     *
     * @var string
     */
    protected $pp;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     *
     * @var bool
     */
    protected $transitional = true;

    /**
     * Constructor.
     *
     * @param string $name Rule name.
     * @param mixed $children Children.
     * @param string $nodeId Node ID.
     */
    public function __construct($name, $children, $nodeId = null)
    {
        $this->setName($name);
        $this->setChildren($children);
        $this->setNodeId($nodeId);
    }

    /**
     * Get rule name.
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rule name.
     *
     * @param string $name Rule name.
     * @return  string
     */
    public function setName($name)
    {
        $old         = $this->name;
        $this->name  = $name;

        return $old;
    }

    /**
     * Get rule's children.
     *
     * @return  mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set rule's children.
     *
     * @param mixed $children Children.
     * @return  mixed
     */
    protected function setChildren($children)
    {
        $old             = $this->children;
        $this->children  = $children;

        return $old;
    }

    /**
     * Get node ID.
     *
     * @return  string
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * Set node ID.
     *
     * @param string $nodeId Node ID.
     * @return  string
     */
    public function setNodeId($nodeId)
    {
        $old = $this->nodeId;

        if (false !== $pos = \strpos((string)$nodeId, ':')) {
            $this->nodeId      = \substr((string)$nodeId, 0, $pos);
            $this->nodeOptions = \str_split(\substr((string)$nodeId, $pos + 1));
        } else {
            $this->nodeId      = $nodeId;
            $this->nodeOptions = [];
        }

        return $old;
    }

    /**
     * Get node options.
     *
     * @retrun  array
     */
    public function getNodeOptions()
    {
        return $this->nodeOptions;
    }

    /**
     * Get default ID.
     *
     * @return  string
     */
    public function getDefaultId()
    {
        return $this->defaultId;
    }

    /**
     * Set default ID.
     *
     * @param string $defaultId Default ID.
     * @return  string
     */
    public function setDefaultId($defaultId)
    {
        $old = $this->defaultId;

        if (false !== $pos = \strpos($defaultId, ':')) {
            $this->defaultId      = \substr($defaultId, 0, $pos);
            $this->defaultOptions = \str_split(\substr($defaultId, $pos + 1));
        } else {
            $this->defaultId      = $defaultId;
            $this->defaultOptions = [];
        }

        return $old;
    }

    /**
     * Get default options.
     *
     * @return  array
     */
    public function getDefaultOptions()
    {
        return $this->defaultOptions;
    }

    /**
     * Set PP representation of the rule.
     *
     * @param string $pp PP representation.
     * @return  string
     */
    public function setPPRepresentation($pp)
    {
        $old                 = $this->pp;
        $this->pp            = $pp;
        $this->transitional  = false;

        return $old;
    }

    /**
     * Get PP representation of the rule.
     *
     * @return  string
     */
    public function getPPRepresentation()
    {
        return $this->pp;
    }

    /**
     * Check whether the rule is transitional or not.
     *
     * @return  bool
     */
    public function isTransitional()
    {
        return $this->transitional;
    }
}
