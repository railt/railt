<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Exceptions\IndeterminateBehaviorException;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Document;

/**
 * Trait LinkingStage
 * @package Railt\Reflection\Common
 */
trait LinkingStage
{
    /**
     * @var array|callable[]
     */
    protected $observers = [];

    /**
     * @var bool
     */
    protected $compiled = false;

    /**
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     */
    public function bootLinkingStage(DocumentTypeInterface $document, TreeNode $ast): void
    {
        foreach (class_uses_recursive($this) as $trait) {
            $name = 'compile' . class_basename($trait);
            if (method_exists($this, $name)) {
                $this->observers[] = [$this, $name];
            }
        }
    }

    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool
    {
        /** @var HasLinkingStageInterface $this */

        if ($this->compiled) {
            return false;
        }

        $ast = $this->getLinkingAstNode();
        $document = $this->getLinkingDocument();

        foreach ($ast->getChildren() as $child) {
            $redefined = $this->compile($document, $child);

            if ($redefined instanceof TreeNode) {
                $child = $redefined;
            }

            foreach ($this->observers as $callable) {
                call_user_func($callable, $document, $child);
            }
        }

        return $this->compiled = true;
    }

    /**
     * @return TreeNode
     */
    private function getLinkingAstNode(): TreeNode
    {
        if (!property_exists($this, 'ast')) {
            throw IndeterminateBehaviorException::new(static::class . '::$ast property is not defined');
        }

        return $this->ast;
    }

    /**
     * @return Document
     */
    private function getLinkingDocument(): Document
    {
        if (!property_exists($this, 'document')) {
            throw IndeterminateBehaviorException::new(static::class . '::$document property is not defined');
        }

        return $this->document;
    }
}
