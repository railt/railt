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
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\EnumTypeInterface;
use Railt\Reflection\Abstraction\EnumValueInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasName;

/**
 * Class EnumValue
 *
 * @package Railt\Reflection\Reflection
 */
class EnumValue extends Definition implements EnumValueInterface
{
    use HasName;
    use Directives;
    use HasDescription;

    /**
     * @var NamedDefinitionInterface
     */
    private $parent;

    /**
     * @var string
     */
    private $value;

    /**
     * EnumValue constructor.
     *
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     * @param EnumTypeInterface $parent
     */
    public function __construct(DocumentTypeInterface $document, TreeNode $ast, EnumTypeInterface $parent)
    {
        $this->parent = $parent;
        parent::__construct($document, $ast);
    }

    /**
     * @return EnumTypeInterface
     */
    public function getParent(): EnumTypeInterface
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if ($this->value === null) {
            /** @var TreeNode $child */
            foreach ($this->ast->getChildren() as $child) {
                if ($child->getId() === '#Name') {
                    $this->value = $child->getChild(0)->getValueValue();
                    break;
                }
            }
        }

        return $this->value;
    }
}
