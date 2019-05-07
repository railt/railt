<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Definitions\Enum;

use Phplrt\Ast\NodeInterface;
use Railt\SDL\Base\Definitions\Enum\BaseValue;
use Railt\SDL\Contracts\Definitions\EnumDefinition;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

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
