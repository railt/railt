<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Definitions\Enum;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Definitions\Enum\BaseValue;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
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
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, EnumDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }
}
