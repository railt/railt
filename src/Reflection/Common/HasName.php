<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasName
 * @package Railgun\Reflection\Common
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
