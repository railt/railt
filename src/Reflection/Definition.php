<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Class Definition
 * @package Serafim\Railgun\Reflection
 */
abstract class Definition implements DefinitionInterface
{
    /**
     * @var Document
     */
    protected $document;

    /**
     * @var TreeNode
     */
    protected $ast;

    /**
     * Definition constructor.
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     */
    public function __construct(DocumentTypeInterface $document, TreeNode $ast)
    {
        $this->ast = $ast;
        $this->document = $document;

        foreach (class_uses_recursive($this) as $trait) {
            $name = 'boot' . class_basename($trait);
            if (method_exists($this, $name)) {
                $this->{$name}($document, $ast);
            }
        }
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'type' => $this->getTypeName(),
            'name' => $this instanceof NamedDefinitionInterface ? $this->getName() : '@anonymous',
            'file' => $this->getDocument()->getFileName(),
            'ast'  => dump($this->ast),
        ];
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return class_basename(static::class);
    }

    /**
     * @return DocumentTypeInterface
     */
    public function getDocument(): DocumentTypeInterface
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this instanceof NamedDefinitionInterface) {
            return $this->getTypeName() . '<' . $this->getName() . '>';
        }

        return $this->getTypeName();
    }
}
