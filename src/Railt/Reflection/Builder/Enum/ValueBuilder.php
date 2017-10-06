<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Enum;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Enum\BaseValue;
use Railt\Reflection\Builder\Compilable;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Contracts\Types\EnumType;

/**
 * Class ValueBuilder
 */
class ValueBuilder extends BaseValue implements Compilable
{
    use Builder;

    /**
     * ValueBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param EnumType $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, EnumType $parent)
    {
        $this->bootBuilder($ast, $document);

        $this->parent = $parent;
        $this->value = $this->getName();
    }
}
