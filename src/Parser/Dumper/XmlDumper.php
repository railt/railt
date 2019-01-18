<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Dumper;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class XmlDumper
 */
class XmlDumper implements NodeDumperInterface
{
    /**
     * @var string
     */
    private const OUTPUT_CHARSET = 'UTF-8';

    /**
     * @var string
     */
    private const OUTPUT_XML_VERSION = '1.1';

    /**
     * @var int
     */
    protected $initialIndention = 0;

    /**
     * @var int
     */
    protected $indention = 4;

    /**
     * @var NodeInterface|mixed
     */
    private $ast;

    /**
     * XmlDumper constructor.
     * @param mixed|NodeInterface $ast
     */
    public function __construct($ast)
    {
        \assert(\class_exists(\DOMDocument::class));

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

        $root = $dom->createElement($this->getRootNodeName());
        $root->appendChild($this->renderAsXml($dom, $this->ast));

        if (\count($root->childNodes) === 1) {
            return $dom->saveXML($root->firstChild);
        }

        return $dom->saveXML($root);
    }

    /**
     * @return string
     */
    private function getRootNodeName(): string
    {
        return $this->getName($this->ast);
    }

    /**
     * @param \DOMDocument $root
     * @param NodeInterface|LeafInterface|RuleInterface|mixed $ast
     * @return \DOMElement
     */
    private function renderAsXml(\DOMDocument $root, $ast): \DOMElement
    {
        if ($ast instanceof LeafInterface) {
            $token = $this->createElement($root, $this->getName($ast), $ast->getValue());
            $this->renderAttributes($root, $token, $ast);

            if (\count($ast->getValues()) > 1) {
                foreach ($ast->getValues() as $i => $value) {
                    if ($i === 0) {
                        continue;
                    }

                    $this->renderAttribute($token, 'value:' . $i, $value);
                }
            }

            return $token;
        }

        $node = $this->createElement($root, $this->getName($ast));
        $this->renderAttributes($root, $node, $ast);

        if ($ast instanceof RuleInterface) {
            /** @var NodeInterface $child */
            foreach ($ast->getChildren() as $child) {
                $node->appendChild($this->renderAsXml($root, $child));
            }
        }

        return $node;
    }

    /**
     * @param \DOMDocument $root
     * @param string $name
     * @param string|null $value
     * @return \DOMElement
     */
    private function createElement(\DOMDocument $root, string $name, string $value = null): \DOMElement
    {
        switch (true) {
            case $value === null:
                return $root->createElement($name);

            case $value === $this->escape($value):
                return $root->createElement($name, $value);

            default:
                $result = $root->createElement($name);
                $result->appendChild($root->createCDATASection($value));
                return $result;
        }
    }

    /**
     * @param NodeInterface $node
     * @return string
     */
    private function getName($node): string
    {
        $name = \basename(\str_replace('\\', '/', \get_class($node)));

        $result = $node instanceof NodeInterface
            ? \preg_replace('/\W+/u', '', $node->getName())
            : $name;

        return $this->escape($result);
    }

    /**
     * @param string $value
     * @return string
     */
    private function escape(string $value): string
    {
        return \htmlspecialchars($value);
    }

    /**
     * @param \DOMDocument $root
     * @param \DOMElement $node
     * @param NodeInterface|mixed $ast
     */
    private function renderAttributes(\DOMDocument $root, \DOMElement $node, $ast): void
    {
        $reflection = new \ReflectionObject($ast);

        /** @var \ReflectionProperty[] $properties */
        $properties = \array_merge(
            $reflection->getProperties(\ReflectionProperty::IS_PROTECTED),
            $reflection->getProperties(\ReflectionProperty::IS_PUBLIC)
        );

        foreach ($properties as $property) {
            $property->setAccessible(true);

            if ($property->isStatic()) {
                continue;
            }

            $value = $property->getValue($ast);

            if (\is_array($value)) {
                foreach ($value as $key => $child) {
                    if (\is_object($child)) {
                        $node->appendChild($this->renderAsXml($root, $child));
                    } else {
                        $this->renderAttribute($node, $property->getName() . ':' . $key, $this->value($child));
                    }
                }
                continue;
            }

            if (\is_object($value)) {
                $node->appendChild($this->renderAsXml($root, $value));
                continue;
            }

            $this->renderAttribute($node, $property->getName(), $this->value($value));
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function value($value): string
    {
        switch (true) {
            case \is_scalar($value):
                return (string)$value;

            case \is_array($value):
                return 'array(' . \count($value) . ') { ... }';

            case \is_object($value):
                return \get_class($value) . '::class';

            default:
                return $this->inline(\print_r($value, true));
        }
    }

    /**
     * @param \DOMElement $node
     * @param string $name
     * @param string $value
     */
    private function renderAttribute(\DOMElement $node, string $name, string $value): void
    {
        $node->setAttribute($this->escape($name), $this->escape($value));
    }

    /**
     * @return string
     * @deprecated Use toString method instead
     */
    public function toXml(): string
    {
        return $this->toString();
    }

    /**
     * @param string $text
     * @return string
     */
    protected function inline(string $text): string
    {
        return \str_replace(["\n", "\r", "\t"], ['\n', '', '\t'], $text);
    }

    /**
     * @param int $depth
     * @param int $initial
     * @return $this
     */
    protected function setIndention(int $depth = 4, int $initial = 0): self
    {
        $this->indention = $depth;
        $this->initialIndention = $initial;

        return $this;
    }

    /**
     * @param string $line
     * @param int $depth
     * @return string
     */
    protected function depth(string $line, int $depth = 0): string
    {
        $prefix = \str_repeat(' ', $depth * ($this->initialIndention + $this->indention));

        return $prefix . $line . \PHP_EOL;
    }
}
