<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Invocations;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Validation\Uniqueness\Scalar\UniqueValueValidator;
use Railt\Reflection\Base\Invocations\BaseInputInvocation;
use Railt\Reflection\Contracts\Definitions\InputDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class InputInvocationBuilder
 */
class InputInvocationBuilder extends BaseInputInvocation implements Compilable
{
    use Compiler;

    /**
     * InputInvocationBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param TypeDefinition $parent
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, TypeDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }

    /**
     * @return null|TypeDefinition|InputDefinition
     */
    public function getTypeDefinition(): ?TypeDefinition
    {
        try {
            return $this->load($this->getName());
        } catch (TypeNotFoundException $error) {
            return null;
        }
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function onCompile(TreeNode $ast): bool
    {
        $key   = $this->key($ast);
        $value = $this->parseValue($ast->getChild(1)->getChild(0), $this->parent);

        if (\array_key_exists($key, $this->values)) {
            $error = \sprintf(UniqueValueValidator::REDEFINITION_ERROR, $key);
            throw new TypeConflictException($error, $this->getCompiler()->getStack());
        }

        $this->values[$key] = $value;

        return true;
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private function key(TreeNode $ast): string
    {
        return (string)$ast->getChild(0)->getChild(0)->getValueValue();
    }
}
