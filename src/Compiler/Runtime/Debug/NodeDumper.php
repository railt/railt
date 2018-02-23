<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Runtime\Debug;

use Railt\Compiler\Runtime\Ast\LeafInterface;
use Railt\Compiler\Runtime\Ast\NodeInterface;
use Railt\Compiler\Runtime\Ast\RuleInterface;

/**
 * Class NodeDumper
 */
class NodeDumper implements Dumper
{
    use DumpHelpers;

    /**
     * @var NodeInterface
     */
    private $ast;

    /**
     * NodeDumper constructor.
     * @param NodeInterface $ast
     */
    public function __construct(NodeInterface $ast)
    {
        $this->ast = $ast;
    }

    /**
     * @param NodeInterface|LeafInterface|RuleInterface $node
     * @param int $depth
     * @return string
     */
    private function renderAsString(NodeInterface $node, int $depth = 0): string
    {
        if ($node instanceof LeafInterface) {
            $token = \vsprintf('token(%s, %s)', [
                $node->getName(),
                $this->inline($node->getValue()),
            ]);

            return $this->depth($token, $depth);
        }


        $result = $this->depth($node->getName(), $depth);

        foreach ($node->getChildren() as $child) {
            $result .= $this->renderAsString($child, $depth + 1);
        }

        return $result;
    }

    /**
     * @param \DOMDocument $root
     * @param NodeInterface|LeafInterface|RuleInterface $ast
     * @return \DOMElement
     */
    private function renderAsXml(\DOMDocument $root, NodeInterface $ast): \DOMElement
    {
        if ($ast instanceof LeafInterface) {
            $token = $root->createElement(\class_basename($ast), $ast->getValue());

            $token->setAttribute('name', $ast->getName());
            $token->setAttribute('offset', (string)$ast->getOffset());

            return $token;
        }

        $node = $root->createElement(\class_basename($ast));
        $node->setAttribute('name', \ltrim($ast->getName(), '#'));

        /** @var NodeInterface $child */
        foreach ($ast->getChildren() as $child) {
            $node->appendChild($this->renderAsXml($root, $child));
        }

        return $node;
    }

    /**
     * @return string
     */
    public function toXml(): string
    {
        $dom               = new \DOMDocument('1.1', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement(\class_basename($this->ast));
        $root->appendChild($this->renderAsXml($dom, $this->ast));

        return $dom->saveXML($root);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return \trim($this->renderAsString($this->ast));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
