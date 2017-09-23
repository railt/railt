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
use Railt\Reflection\Builder\Support\Fields;
use Railt\Reflection\Contracts\Types\InterfaceType;

/**
 * Class InterfaceBuilder
 */
class InterfaceBuilder implements InterfaceType
{
    use Fields;
    use NamedTypeBuilder;

    /**
     * InterfaceBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootNamedTypeBuilder($ast, $document);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Interface';
    }
}
