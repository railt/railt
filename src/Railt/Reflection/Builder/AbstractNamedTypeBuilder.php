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
use Railt\Reflection\Builder\Support\NameBuilder;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Class AbstractNamedTypeBuilder
 */
abstract class AbstractNamedTypeBuilder extends AbstractTypeBuilder implements NamedTypeInterface
{
    use NameBuilder;

    /**
     * AbstractNamedTypeBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws BuildingException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        parent::__construct($ast, $document);

        $this->bootNameBuilder($ast);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->getName() ?: 'Null';
    }
}
