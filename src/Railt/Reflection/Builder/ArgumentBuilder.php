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
use Railt\Reflection\Builder\Support\Directives;
use Railt\Reflection\Builder\Support\TypeIndication;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\ArgumentType;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends AbstractNamedTypeBuilder implements ArgumentType
{
    use Directives;
    use TypeIndication;

    /**
     *
     */
    protected const AST_ID_DEFAULT_VALUE = '#Value';

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
        parent::__construct($ast, $document);
    }

    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === static::AST_ID_DEFAULT_VALUE) {
            $this->hasDefaultValue = true;
            $this->defaultValue = ValueCoercion::parse($ast->getChild(0));
            return true;
        }

        return false;
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
}
