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
 * Class Rule
 */
abstract class Rule implements Arrayable
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
     * @var array|int|null
     */
    protected $children;

    /**
     * Node ID.
     *
     * @var string
     */
    protected $nodeId;

    /**
     * Constructor.
     *
     * @param string $name Rule name.
     * @param mixed $children Children.
     * @param string $nodeId Node ID.
     */
    public function __construct($name, $children, $nodeId = null)
    {
        $this->name     = $name;
        $this->children = $children;
        $this->nodeId   = $nodeId;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->name,
            $this->children,
            $this->nodeId,
        ];
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
     * @param $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function setChildrent($items)
    {
        $this->children = $items;
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
     * @param $nodeId
     */
    public function setNodeId($nodeId): void
    {
        $this->nodeId = $nodeId;
    }
}
