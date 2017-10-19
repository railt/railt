<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Dependent;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Dependent\BaseField;
use Railt\Reflection\Builder\Behavior\TypeIndicationBuilder;
use Railt\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Contracts\Behavior\Nameable;

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
     * @param Nameable $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        $this->parent = $parent;
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        return false;
    }
}
