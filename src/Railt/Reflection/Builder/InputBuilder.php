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
use Railt\Reflection\Base\BaseInput;
use Railt\Reflection\Builder\Support\ArgumentsBuilder;
use Railt\Reflection\Builder\Support\Builder;

/**
 * Class InputBuilder
 */
class InputBuilder extends BaseInput implements Compilable
{
    use Builder;
    use ArgumentsBuilder;

    /**
     * InputBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
    }
}
