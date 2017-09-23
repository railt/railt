<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Runtime;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Support\Deprecation;
use Railt\Reflection\Builder\Support\Directives;
use Railt\Reflection\Builder\Support\NameBuilder;

/**
 * Trait NamedTypeBuilder
 */
trait NamedTypeBuilder
{
    use TypeBuilder;
    use NameBuilder;
    use Directives;
    use Deprecation;

    /**
     * AbstractNamedTypeBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    protected function bootNamedTypeBuilder(TreeNode $ast, DocumentBuilder $document): void
    {
        $this->bootTypeBuilder($ast, $document);
        $this->bootNameBuilder($ast);
    }
}
