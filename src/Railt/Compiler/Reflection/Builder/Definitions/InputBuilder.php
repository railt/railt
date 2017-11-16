<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Definitions;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Definitions\BaseInput;
use Railt\Compiler\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;

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
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('input');
    }
}
