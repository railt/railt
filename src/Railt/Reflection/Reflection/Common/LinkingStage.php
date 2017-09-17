<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Reflection\Reflection\Document;
/**
 * Trait LinkingStage
 * @package Railt\Reflection\Reflection\Common
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
     * @param DocumentInterface $document
     * @param TreeNode $ast
     */
    public function bootLinkingStage(DocumentInterface $document, TreeNode $ast): void
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

        if (method_exists($this, '__toString')) {
            $this->debug('Run ' . (string)$this . ' linker');
        }

        $ast      = $this->getLinkingAstNode();
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

        $this->complete($document, $ast);

        return $this->compiled = true;
    }

    /**
     * @return TreeNode
     * @throws \LogicException
     */
    private function getLinkingAstNode(): TreeNode
    {
        if (! property_exists($this, 'ast')) {
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
        if (! property_exists($this, 'document')) {
            throw new \LogicException(static::class . '::$document property is not defined');
        }

        return $this->document;
    }

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        // Compilation process

        return $ast;
    }

    /**
     * @param Document $document
     * @param TreeNode $ast
     */
    public function complete(Document $document, TreeNode $ast): void
    {
        // Post compilation event
    }
}
