<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Parser\Dumper;

use Railt\Component\Parser\Ast\LeafInterface;
use Railt\Component\Parser\Ast\NodeInterface;
use Railt\Component\Parser\Ast\RuleInterface;

/**
 * Class HoaDumper
 */
class HoaDumper implements NodeDumperInterface
{
    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * HoaDumper constructor.
     *
     * @param RuleInterface $ast
     */
    public function __construct(RuleInterface $ast)
    {
        $this->ast = $ast;
    }

    /**
     * @param NodeInterface|RuleInterface|LeafInterface $node
     * @param int $depth
     * @return array
     */
    private function render(NodeInterface $node, int $depth = 1): array
    {
        $prefix = \str_repeat('>  ', $depth);

        if ($node instanceof LeafInterface) {
            return [
                $prefix . 'token(' . $node->getName() . ', ' . $node->getValue() . ')',
            ];
        }

        $result = [$prefix . $node->getName()];

        foreach ($node->getChildren() as $child) {
            $result = \array_merge($result, $this->render($child, $depth + 1));
        }

        return $result;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return \implode("\n", $this->render($this->ast));
    }
}
