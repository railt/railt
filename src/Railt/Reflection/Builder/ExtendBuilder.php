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
use Railt\Reflection\Base\BaseExtend;
use Railt\Reflection\Builder\Coercion\Inheritance;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Containers\HasArguments;
use Railt\Reflection\Contracts\Containers\HasDirectives;
use Railt\Reflection\Contracts\Containers\HasFields;
use Railt\Reflection\Contracts\Types\ExtendType;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class ExtendBuilder
 */
class ExtendBuilder extends BaseExtend implements Compilable
{
    use Builder;

    /**
     * @var Inheritance
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
        $this->inheritance = new Inheritance();
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        $type = DocumentBuilder::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        if ($type !== null && !($type instanceof ExtendType)) {
            $this->applyExtender(new $type($ast, $this->getDocument()));
        }

        return false;
    }

    /**
     * @param Nameable|TypeInterface|Compilable $instance
     * @return void
     * @throws TypeConflictException
     */
    private function applyExtender(TypeInterface $instance): void
    {
        $instance->compileIfNotCompiled();

        /** @var TypeInterface $original */
        $original = $this->getCompiler()->get($instance->getName());

        $this->extend($original, $instance);
    }

    /**
     * @param TypeInterface $original
     * @param TypeInterface $extend
     * @return void
     */
    private function extend(TypeInterface $original, TypeInterface $extend): void
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
     * @throws TypeConflictException
     */
    private function extendFields(HasFields $original, HasFields $extend): void
    {
        foreach ($extend->getFields() as $extendField) {
            if ($original->hasField($extendField->getName())) {
                /** @var FieldType $field */
                $field = $original->getField($extendField->getName());

                $this->inheritance->checkType($field, $extendField);

                $this->extendArguments($field, $extendField);
            }
        }
    }

    /**
     * @param HasArguments $original
     * @param HasArguments $extend
     * @return void
     */
    private function extendArguments(HasArguments $original, HasArguments $extend): void
    {
        foreach ($extend->getArguments() as $argument) {
            dd($argument);
        }
    }

    /**
     * @param HasDirectives $original
     * @param HasDirectives $extend
     * @return void
     */
    private function extendDirectives(HasDirectives $original, HasDirectives $extend): void
    {
        foreach ($extend->getDirectives() as $directives) {
            dd($directives);
        }
    }
}
