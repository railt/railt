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
abstract class Rule
{
    /**
     * Rule name.
     * @var string|int
     */
    protected $name;

    /**
     * Rule's children. Can be an array of names or a single name.
     * @var array|string
     */
    protected $children;

    /**
     * Node ID.
     * @var string|null
     */
    protected $nodeId;

    /**
     * Rule constructor.
     * @param int|string $name
     * @param string|array $children
     * @param string|null $nodeId
     */
    public function __construct($name, $children, string $nodeId = null)
    {
        $this->setName($name);
        $this->setChildren($children);

        if ($nodeId) {
            $this->setNodeId($nodeId);
        }
    }

    /**
     * @return array
     */
    public function args(): array
    {
        return [
            $this->name,
            $this->children,
            $this->nodeId,
        ];
    }

    /**
     * Get rule name.
     * @return int|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rule name.
     *
     * @param int|string $name Rule name.
     * @return self|$this
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get rule's children.
     * @return array|string
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set rule's children.
     * @param string|array $children Children.
     * @return self|$this
     */
    protected function setChildren($children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get node ID.
     * @return string|null
     */
    public function getNodeId(): ?string
    {
        return $this->nodeId;
    }

    /**
     * Set node ID.
     * @param string $nodeId Node ID.
     * @return self|$this
     */
    public function setNodeId(string $nodeId): self
    {
        $this->nodeId = $nodeId;

        return $this;
    }
}
