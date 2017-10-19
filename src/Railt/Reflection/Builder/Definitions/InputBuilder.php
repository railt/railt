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
use Railt\Reflection\Base\Definitions\BaseInput;
use Railt\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;

/**
 * Class InputBuilder
 */
class InputBuilder extends BaseInput implements Compilable
{
    use Compiler;
    use ArgumentsBuilder;
    use DirectivesBuilder;

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
