<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Definitions;

use Railt\Compiler\Ast\NodeInterface;
use Railt\GraphQL\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\GraphQL\Reflection\Builder\DocumentBuilder;
use Railt\GraphQL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compilable;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Definitions\BaseInput;

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
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('input');
    }
}
