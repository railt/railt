<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\DefinitionInterface;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Support\Log\Loggable;

/**
 * Class Definition
 * @package Railt\Reflection\Reflection
 */
abstract class Definition implements DefinitionInterface
{
    use Loggable;

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
     * @param DocumentInterface $document
     * @param TreeNode $ast
     */
    public function __construct(DocumentInterface $document, TreeNode $ast)
    {
        $this->ast      = $ast;
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
     * @return DocumentInterface
     */
    public function getDocument(): DocumentInterface
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

    /**
     * @internal This method for debug usage only
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
        return $this->ast;
    }
}
