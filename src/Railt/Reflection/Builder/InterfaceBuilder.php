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
use Railt\Reflection\Base\BaseInterface;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Builder\Support\FieldsBuilder;

/**
 * Class InterfaceBuilder
 */
class InterfaceBuilder extends BaseInterface implements Compilable
{
    use Builder;
    use FieldsBuilder;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
    }
}
