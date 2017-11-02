<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Dependent;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Base\Dependent\BaseField;
use Railt\Compiler\Reflection\Builder\Behavior\TypeIndicationBuilder;
use Railt\Compiler\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class FieldBuilder
 */
class FieldBuilder extends BaseField implements Compilable
{
    use Compiler;
    use ArgumentsBuilder;
    use DirectivesBuilder;
    use TypeIndicationBuilder;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param TypeDefinition $parent
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, TypeDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }
}
