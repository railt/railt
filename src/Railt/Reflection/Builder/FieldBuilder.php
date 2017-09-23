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
use Railt\Reflection\Builder\Support\Arguments;
use Railt\Reflection\Builder\Support\TypeIndication;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\FieldType;

/**
 * Class FieldBuilder
 */
class FieldBuilder implements FieldType
{
    use Arguments;
    use TypeIndication;
    use NamedTypeBuilder;

    /**
     * @var Nameable
     */
    private $parent;

    /**
     * FieldBuilder constructor.
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
     * @return Inputable
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function getType(): Inputable
    {
        \assert($this->typeName !== null, 'Broken AST, #Type node required');

        return $this->onlyInputable($this->getCompiler()->get($this->typeName));
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
        return 'Field';
    }
}
