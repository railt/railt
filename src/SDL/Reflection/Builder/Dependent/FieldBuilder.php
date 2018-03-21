<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Dependent;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Reflection\Base\Dependent\BaseField;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Reflection\Builder\Behavior\TypeIndicationBuilder;
use Railt\SDL\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

/**
 * Class FieldBuilder
 */
class FieldBuilder extends BaseField implements Compilable
{
    use Compiler;
    use ArgumentsBuilder;
    use DirectivesBuilder;
    use TypeIndicationBuilder;

    /**
     * SchemaBuilder constructor.
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @param TypeDefinition $parent
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document, TypeDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }
}
