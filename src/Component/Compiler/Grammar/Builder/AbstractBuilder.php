<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Grammar\Builder;

use Railt\Component\Parser\Rule\Rule;

/**
 * Class AbstractBuilder
 */
abstract class AbstractBuilder
{
    /**
     * @var int|string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $nodeId;

    /**
     * @var string|null
     */
    protected $defaultId;

    /**
     * @var int|int[]|string|string[]
     */
    protected $children;

    /**
     * Rule constructor.
     * @param string|int $name Rule name.
     * @param int|int[]|string|string[] $children Children.
     * @param string $nodeId Node ID.
     */
    public function __construct($name, $children, string $nodeId = null)
    {
        $this->name = $name;
        $this->children = $children;
        $this->nodeId = $nodeId;
    }

    /**
     * @param $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return Rule
     */
    abstract public function build(): Rule;

    /**
     * @param $nodeId
     */
    public function setNodeId($nodeId): void
    {
        $this->nodeId = $nodeId;
    }

    /**
     * @param $defaultNodeId
     */
    public function setDefaultId($defaultNodeId): void
    {
        $this->defaultId = $defaultNodeId;
    }
}
