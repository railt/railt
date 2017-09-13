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
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasName
 * @package Railt\Reflection\Reflection\Common
 */
trait HasName
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     */
    public function bootHasName(DocumentTypeInterface $document, TreeNode $ast): void
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($this instanceof NamedDefinitionInterface) {
                $allowed = in_array($child->getId(), $this->astHasName ?? ['#Name'], true);

                if ($allowed) {
                    $this->name = $child->getChild(0)->getValueValue();
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }
}
