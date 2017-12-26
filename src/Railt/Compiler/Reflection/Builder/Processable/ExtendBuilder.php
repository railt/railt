<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Processable;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Validation\Inheritance;
use Railt\Reflection\Base\Dependent\Argument\BaseArgumentsContainer;
use Railt\Reflection\Base\Dependent\BaseArgument;
use Railt\Reflection\Base\Dependent\BaseField;
use Railt\Reflection\Base\Dependent\Field\BaseFieldsContainer;
use Railt\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Reflection\Base\Processable\BaseExtend;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Invocations\Directive\HasDirectives;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Reflection\Contracts\Processable\ExtendDefinition;

/**
 * Class ExtendBuilder
 */
class ExtendBuilder extends BaseExtend implements Compilable
{
    use Compiler;

    /**
     * ExtendBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws TypeConflictException
     * @throws \Exception
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeConflictException
     * @throws \OutOfBoundsException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        $type = DocumentBuilder::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        if ($type !== null && ! ($type instanceof ExtendDefinition)) {
            /** @var Compilable $virtualType */
            $virtualType = new $type($ast, $this->getDocument());

            $virtualType->compile();

            $this->applyExtender($virtualType);
        }

        return false;
    }

    /**
     * @param Definition|Compilable $instance
     * @return void
     * @throws TypeConflictException
     * @throws \OutOfBoundsException
     */
    private function applyExtender(Definition $instance): void
    {
        $this->type = $this->load($instance->getName());

        $this->extend($this->type, $instance);
    }

    /**
     * @param Definition $original
     * @param Definition $extend
     * @return void
     * @throws TypeConflictException
     * @throws \OutOfBoundsException
     */
    private function extend(Definition $original, Definition $extend): void
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
     * @param HasFields $original
     * @param HasFields $extend
     * @return void
     * @throws \OutOfBoundsException
     * @throws TypeConflictException
     */
    private function extendFields(HasFields $original, HasFields $extend): void
    {
        foreach ($extend->getFields() as $extendField) {
            if ($original->hasField($extendField->getName())) {
                /**
                 * Check field type.
                 * @var FieldDefinition $field
                 */
                $field = $original->getField($extendField->getName());

                $this->getValidator(Inheritance::class)->validate($extendField, $field);

                $this->dataFieldExtender()->call($field, $extendField);

                /**
                 * Check field arguments
                 */
                $this->extendArguments($field, $extendField);
                continue;
            }

            $callee = function () use ($extendField): void {
                /** @var BaseFieldsContainer $this */
                $this->fields[$extendField->getName()] = $extendField;
            };

            $callee->call($original);
        }
    }

    /**
     * @return \Closure
     */
    private function dataFieldExtender(): \Closure
    {
        /** @var FieldDefinition|BaseField $field */
        return function (FieldDefinition $field): void {
            /** @var BaseField $this */

            // Extend type
            $this->type = $field->type;

            // Extend deprecation reason
            $this->deprecationReason = $field->deprecationReason ?: $this->deprecationReason;

            // Extend description
            $this->description = $field->description ?: $this->description;

            // NonNull overriding
            $this->isNonNull = $field->isNonNull();
            $this->isListOfNonNulls = $field->isListOfNonNulls();
        };
    }

    /**
     * @param HasArguments|BaseArgumentsContainer|DirectiveInvocation $original
     * @param HasArguments|DirectiveInvocation $extend
     * @return void
     * @throws \OutOfBoundsException
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function extendArguments($original, $extend): void
    {
        foreach ($extend->getArguments() as $extendArgument) {
            if ($original->hasArgument($extendArgument->getName())) {
                /**
                 * Check field type.
                 * @var ArgumentDefinition $argument
                 */
                $argument = $original->getArgument($extendArgument->getName());

                $this->getValidator(Inheritance::class)->validate($extendArgument, $argument);

                $this->dataArgumentExtender()->call($argument, $extendArgument);

                continue;
            }

            $callee = function () use ($extendArgument): void {
                /** @var BaseArgumentsContainer $this */
                $this->arguments[$extendArgument->getName()] = $extendArgument;
            };

            $callee->call($original);
        }
    }

    /**
     * @return \Closure
     */
    private function dataArgumentExtender(): \Closure
    {
        /** @var ArgumentDefinition|BaseArgument $argument */
        return function (ArgumentDefinition $argument): void {
            /** @var BaseArgument $this */

            // Extend type
            $this->type = $argument->type;

            // Extend deprecation reason
            $this->deprecationReason = $argument->deprecationReason ?: $this->deprecationReason;

            // Extend description
            $this->description = $argument->description ?: $this->description;

            // NonNull overriding
            $this->isNonNull = $argument->isNonNull();
            $this->isListOfNonNulls = $argument->isListOfNonNulls();
        };
    }

    /**
     * @param HasDirectives|BaseDirectivesContainer $original
     * @param HasDirectives $extend
     * @return void
     * @throws \OutOfBoundsException
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

            $callee = function () use ($extendDirective): void {
                /** @var BaseArgumentsContainer $this */
                $this->arguments[$extendDirective->getName()] = $extendDirective;
            };

            $callee->call($original);
        }
    }
}
