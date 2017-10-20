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
use Railt\Reflection\Base\Dependent\BaseArgument;
use Railt\Reflection\Builder\Behavior\TypeIndicationBuilder;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Builder\Process\ValueBuilder;
use Railt\Reflection\Compiler\Support;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Exceptions\TypeConflictException;

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
        if ($ast->getId() === '#Value') {
            $this->hasDefaultValue = true;
            $this->defaultValue = ValueBuilder::parse($ast->getChild(0));

            return true;
        }

        return false;
    }

    /**
     * @return void
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function verify(): void
    {
        if ($this->hasDefaultValue()) {
            switch (true) {
                case $this->getDefaultValue() === null:
                    $this->verifyNullDefaultValue();
                    break;

                case \is_array($this->getDefaultValue()):
                    $this->verifyArrayDefaultValue();
                    break;
            }
        }
    }

    /**
     * @return void
     * @throws TypeConflictException
     */
    private function verifyNullDefaultValue(): void
    {
        /**
         * Will throw an Exception when NonNull type like "argument: Type!"
         * initialized by default "null" value.
         */
        if ($this->isNonNull()) {
            $error = \sprintf('Default non-null value of %s can not be null', $this->typeToString($this));
            throw new TypeConflictException($error);
        }
    }

    /**
     * @return void
     * @throws TypeConflictException
     */
    private function verifyArrayDefaultValue(): void
    {
        /**
         * Will throw an Exception when non-list type type like "argument: Type"
         * initialized by default list value.
         */
        if (! $this->isList()) {
            $error = \sprintf('Default non-list value of %s can not be list', $this->typeToString($this));
            throw new TypeConflictException($error);
        }

        /**
         * Will throw an Exception when list of non-nulls type like
         * "argument: [Type!]" or "argument: [Type!]!" contain "null".
         */
        if ($this->isListOfNonNulls() && \in_array(null, (array)$this->getDefaultValue(), true)) {
            $error = \sprintf('Default list value of %s type can not contain nulls', $this->typeToString($this));
            throw new TypeConflictException($error);
        }
    }
}

