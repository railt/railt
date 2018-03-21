<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Definitions;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Reflection\Base\Definitions\BaseUnion;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

/**
 * Class UnionBuilder
 */
class UnionBuilder extends BaseUnion implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * UnionBuilder constructor.
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('union');
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     * @throws TypeConflictException
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        if ($ast->is('#Relations')) {
            foreach ($ast->getChildren() as $relation) {
                $name = $relation->getChild(0)->getValue();

                $child = $this->load($name);

                $this->types = $this->unique($this->types, $child);
            }

            return true;
        }

        return false;
    }
}
