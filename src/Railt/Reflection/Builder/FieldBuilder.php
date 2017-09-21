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
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Builder\Support\Arguments;
use Railt\Reflection\Builder\Support\Directives;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Builder\Support\TypeIndication;

/**
 * Class FieldBuilder
 */
class FieldBuilder extends AbstractNamedTypeBuilder implements FieldType
{
    use Arguments;
    use Directives;
    use TypeIndication;

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
        parent::__construct($ast, $document);
    }

    /**
     * @return Nameable
     */
    public function getParent(): Nameable
    {
        return $this->parent;
    }
}
