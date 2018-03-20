<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Debug;

use Railt\Compiler\Parser\Ast\LeafInterface;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;

/**
 * Class NodeDumper
 */
class NodeDumper extends BaseDumper
{
    private const OUTPUT_CHARSET = 'UTF-8';
    private const OUTPUT_XML_VERSION = '1.1';

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
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $dom = new \DOMDocument(self::OUTPUT_XML_VERSION, self::OUTPUT_CHARSET);

        $dom->formatOutput = true;

        $root = $dom->createElement(\class_basename($this->ast));
        $root->appendChild($this->renderAsXml($dom, $this->ast));

        return $dom->saveXML($root);
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

            return $this->extractAttributes($ast, $token);
        }

        $node = $root->createElement(\class_basename($ast));
        $node = $this->extractAttributes($ast, $node);

        /** @var NodeInterface $child */
        foreach ($ast->getChildren() as $child) {
            $node->appendChild($this->renderAsXml($root, $child));
        }

        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param \DOMElement $dom
     * @return \DOMElement
     */
    private function extractAttributes(NodeInterface $node, \DOMElement $dom): \DOMElement
    {
        $reflection = new \ReflectionObject($node);

        foreach ($this->properties($reflection, $node) as $name => $value) {
            if (\in_array($name, ['value', 'children'], true)) {
                continue;
            }

            if (\is_array($value) || \is_object($value)) {
                $value = @\json_encode($value);
            }

            $dom->setAttribute($name, (string)$value);
        }

        return $dom;
    }

    /**
     * @param \ReflectionClass $object
     * @param NodeInterface $node
     * @return \Traversable
     */
    private function properties(\ReflectionClass $object, NodeInterface $node): \Traversable
    {
        foreach ($object->getProperties() as $property) {
            $property->setAccessible(true);

            yield $property->getName() => $property->getValue($node);
        }

        if ($object->getParentClass()) {
            yield from $this->properties($object->getParentClass(), $node);
        }
    }
}
