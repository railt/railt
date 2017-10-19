<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Definitions\Enum;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Definitions\Enum\BaseValue;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Contracts\Definitions\EnumDefinition;

/**
 * Class ValueBuilder
 */
class ValueBuilder extends BaseValue implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * ValueBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param EnumDefinition $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, EnumDefinition $parent)
    {
        $this->bootBuilder($ast, $document);
        $this->parent = $parent;
    }
}
