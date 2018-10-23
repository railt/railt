<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Finder;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class Filter
 */
class Filter
{
    /**
     * @var \Closure
     */
    private $expr;

    /**
     * @var Depth
     */
    public $depth;

    /**
     * Expression constructor.
     * @param \Closure $expr
     * @param Depth $depth
     */
    public function __construct(\Closure $expr, Depth $depth)
    {
        $this->expr = $expr;
        $this->depth = $depth;
    }

    /**
     * @param Depth $depth
     * @return Filter
     */
    public static function any(Depth $depth): Filter
    {
        return new static(function (): bool {
            return true;
        }, $depth);
    }

    /**
     * @param string $name
     * @param Depth $depth
     * @return Filter
     */
    public static function node(string $name, Depth $depth): Filter
    {
        return new static(function (NodeInterface $node) use ($name): bool {
            return $node->is($name);
        }, $depth);
    }

    /**
     * @param string $name
     * @param Depth $depth
     * @return Filter
     */
    public static function leaf(string $name, Depth $depth): Filter
    {
        return new static(function (NodeInterface $node) use ($name): bool {
            return $node instanceof LeafInterface && $node->is($name);
        }, $depth);
    }

    /**
     * @param string $name
     * @param Depth $depth
     * @return Filter
     */
    public static function rule(string $name, Depth $depth): Filter
    {
        return new static(function (NodeInterface $node) use ($name): bool {
            return $node instanceof RuleInterface && $node->is($name);
        }, $depth);
    }

    /**
     * @param NodeInterface $node
     * @param int $depth
     * @return bool
     */
    public function match(NodeInterface $node, int $depth): bool
    {
        return $this->depth->match($depth) && (bool)($this->expr)($node);
    }
}
