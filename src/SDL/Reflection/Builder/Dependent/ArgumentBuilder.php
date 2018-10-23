<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Dependent;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Base\Dependent\BaseArgument;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Reflection\Builder\Behavior\TypeIndicationBuilder;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;
use Railt\SDL\Support;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends BaseArgument implements Compilable
{
    use Support;
    use Compiler;
    use DirectivesBuilder;
    use TypeIndicationBuilder;

    /**
     * ArgumentBuilder constructor.
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

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        if ($ast->is('Value')) {
            $this->hasDefaultValue = true;
            $this->defaultValue    = $this->parseValue(
                $ast->getChild(0),
                $this->getTypeDefinition()->getName()
            );

            return true;
        }

        return false;
    }
}
