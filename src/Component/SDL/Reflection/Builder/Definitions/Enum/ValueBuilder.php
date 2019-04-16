<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Builder\Definitions\Enum;

use Railt\Component\Parser\Ast\NodeInterface;
use Railt\Component\SDL\Base\Definitions\Enum\BaseValue;
use Railt\Component\SDL\Contracts\Definitions\EnumDefinition;
use Railt\Component\SDL\Reflection\Builder\DocumentBuilder;
use Railt\Component\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Component\SDL\Reflection\Builder\Process\Compilable;
use Railt\Component\SDL\Reflection\Builder\Process\Compiler;

/**
 * Class ValueBuilder
 */
class ValueBuilder extends BaseValue implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * ValueBuilder constructor.
     *
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @param EnumDefinition $parent
     * @throws \OutOfBoundsException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document, EnumDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }
}
