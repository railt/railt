<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Definitions;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Definitions\BaseScalar;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;

/**
 * Class ScalarBuilder
 */
class ScalarBuilder extends BaseScalar implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * ScalarBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
    }
}
