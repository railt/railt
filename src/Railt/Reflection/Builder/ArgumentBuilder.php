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
use Railt\Reflection\Builder\Runtime\NamedTypeBuilder;
use Railt\Reflection\Builder\Support\TypeIndication;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\ArgumentType;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder implements ArgumentType
{
    use TypeIndication;
    use NamedTypeBuilder;

    private const AST_ID_ARGUMENT_VALUE = '#Value';

    /**
     * @var Nameable
     */
    private $parent;

    /**
     * @var bool
     */
    private $hasDefaultValue = false;

    /**
     * @var mixed|null
     */
    private $defaultValue;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param Nameable $parent
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        $this->parent = $parent;
        $this->bootNamedTypeBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === self::AST_ID_ARGUMENT_VALUE) {
            $this->hasDefaultValue = true;
            $this->defaultValue    = ValueCoercion::parse($ast->getChild(0));

            return true;
        }

        return false;
    }

    /**
     * @return Inputable
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function getType(): Inputable
    {
        \assert($this->typeName !== null, 'Broken AST, #Type node required');

        return $this->onlyInputable($this->getCompiler()->get($this->typeName));
    }

    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return $this->compiled()->defaultValue;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->compiled()->hasDefaultValue;
    }

    /**
     * @return Nameable
     */
    public function getParent(): Nameable
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Argument';
    }
}
