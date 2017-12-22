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
use Railt\Compiler\Reflection\Builder\Behavior\TypeIndicationBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Dependent\BaseArgument;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Support;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends BaseArgument implements Compilable
{
    use Support;
    use Compiler;
    use DirectivesBuilder;
    use TypeIndicationBuilder;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param TypeDefinition $parent
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, TypeDefinition $parent)
    {
        $this->parent       = $parent;
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function onCompile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Value') {
            $this->hasDefaultValue = true;
            $this->defaultValue = $this->valueCoercion(
                $this->parseValue($ast->getChild(0), $this)
            );

            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return array|mixed
     */
    private function valueCoercion($value)
    {
        return $this->isList() ? (array)$value : $value;
    }
}
