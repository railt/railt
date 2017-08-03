<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Document;

/**
 * Trait LinkingStage
 * @package Serafim\Railgun\Reflection\Common
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
     * @throws \LogicException
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
     * @throws \LogicException
     */
    private function getLinkingAstNode(): TreeNode
    {
        if (!property_exists($this, 'ast')) {
            throw new \LogicException(static::class . '::$ast property is not defined');
        }

        return $this->ast;
    }

    /**
     * @return Document
     * @throws \LogicException
     */
    private function getLinkingDocument(): Document
    {
        if (!property_exists($this, 'document')) {
            throw new \LogicException(static::class . '::$document property is not defined');
        }

        return $this->document;
    }
}
