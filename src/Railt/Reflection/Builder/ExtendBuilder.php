<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\BaseArgument;
use Railt\Reflection\Base\BaseExtend;
use Railt\Reflection\Base\BaseField;
use Railt\Reflection\Base\Containers\BaseArgumentsContainer;
use Railt\Reflection\Base\Containers\BaseDirectivesContainer;
use Railt\Reflection\Base\Containers\BaseFieldsContainer;
use Railt\Reflection\Builder\Inheritance\TypeInheritance;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Containers\HasArguments;
use Railt\Reflection\Contracts\Containers\HasDirectives;
use Railt\Reflection\Contracts\Containers\HasFields;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Contracts\Types\ExtendType;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Types\TypeDefinition;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class ExtendBuilder
 */
class ExtendBuilder extends BaseExtend implements Compilable
{
    use Builder;

    /**
     * @var TypeInheritance
     */
    private $inheritance;

    /**
     * ExtendBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
        $this->inheritance = new TypeInheritance();

        // Force compilation
        $this->compileIfNotCompiled();
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function compile(TreeNode $ast): bool
    {
        $type = DocumentBuilder::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        if ($type !== null && ! ($type instanceof ExtendType)) {
            $this->applyExtender(new $type($ast, $this->getDocument()));
        }

        return false;
    }

    /**
     * @param Nameable|TypeDefinition|Compilable $instance
     * @return void
     * @throws TypeConflictException
     */
    private function applyExtender(TypeDefinition $instance): void
    {
        /** @var TypeDefinition $original */
        $original = $this->getCompiler()->get($instance->getName());

        $this->extend($original, $instance);
    }

    /**
     * @param TypeDefinition $original
     * @param TypeDefinition $extend
     * @return void
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function extend(TypeDefinition $original, TypeDefinition $extend): void
    {
        if ($original instanceof HasFields && $extend instanceof HasFields) {
            $this->extendFields($original, $extend);
        }

        if ($original instanceof HasDirectives && $extend instanceof HasDirectives) {
            $this->extendDirectives($original, $extend);
        }

        if ($original instanceof HasArguments && $extend instanceof HasArguments) {
            $this->extendArguments($original, $extend);
        }
    }

    /**
     * @param HasFields|BaseFieldsContainer $original
     * @param HasFields $extend
     * @return void
     * @throws TypeConflictException
     */
    private function extendFields(HasFields $original, HasFields $extend): void
    {
        foreach ($extend->getFields() as $extendField) {
            if ($original->hasField($extendField->getName())) {
                /**
                 * Check field type.
                 * @var FieldType|BaseField $field
                 */
                $field = $original->getField($extendField->getName());

                $this->inheritance->verify($field, $extendField);
                $this->dataFieldExtender()->call($field, $extendField);

                /**
                 * Check field arguments
                 */
                $this->extendArguments($field, $extendField);

                continue;
            }

            $original->addField($extendField);
        }
    }

    /**
     * @return \Closure
     */
    private function dataFieldExtender(): \Closure
    {
        return function(FieldType $field): void {
            // Extend type
            $this->type = $field->type;

            // Extend deprecation reason
            $this->deprecationReason = $field->deprecationReason ?: $this->deprecationReason;

            // Extend description
            $this->description = $field->description ?: $this->description;
        };
    }

    /**
     * @param HasArguments|BaseArgumentsContainer|DirectiveInvocation $original
     * @param HasArguments|DirectiveInvocation $extend
     * @return void
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function extendArguments($original, $extend): void
    {
        foreach ($extend->getArguments() as $extendArgument) {
            if ($original->hasArgument($extendArgument->getName())) {
                /**
                 * Check field type.
                 * @var ArgumentType|BaseArgument $argument
                 */
                $argument = $original->getArgument($extendArgument->getName());

                $this->inheritance->verify($argument, $extendArgument);
                $this->dataArgumentExtender()->call($argument, $extendArgument->getType());

                continue;
            }

            $original->addArgument($extendArgument);
        }
    }

    /**
     * @return \Closure
     */
    private function dataArgumentExtender(): \Closure
    {
        return function(ArgumentType $argument): void {
            // Extend type
            $this->type = $argument->type;

            // Extend deprecation reason
            $this->deprecationReason = $argument->deprecationReason ?: $this->deprecationReason;

            // Extend description
            $this->description = $argument->description ?: $this->description;
        };
    }

    /**
     * @param HasDirectives|BaseDirectivesContainer $original
     * @param HasDirectives $extend
     * @return void
     * @throws TypeConflictException
     */
    private function extendDirectives(HasDirectives $original, HasDirectives $extend): void
    {
        foreach ($extend->getDirectives() as $extendDirective) {
            if ($original->hasDirective($extendDirective->getName())) {
                /** @var DirectiveInvocation $directive */
                $directive = $original->getDirective($extendDirective->getName());

                $this->extendArguments($directive, $extendDirective);
                continue;
            }

            $original->addDirective($extendDirective);
        }
    }
}
