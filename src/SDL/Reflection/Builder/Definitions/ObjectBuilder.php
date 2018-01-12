<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Definitions;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Reflection\Base\Definitions\BaseObject;
use Railt\SDL\Reflection\Builder\Dependent\Field\FieldsBuilder;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends BaseObject implements Compilable
{
    use Compiler;
    use FieldsBuilder;
    use DirectivesBuilder;

    /**
     * SchemaBuilder constructor.
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('type');
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        if ($ast->is('#Implements')) {
            foreach ($ast->getChildren() as $child) {
                $name = $child->getChild(0)->getValue();

                $interface = $this->load($name);

                $this->interfaces = $this->unique($this->interfaces, $interface);
            }

            return true;
        }

        return false;
    }
}
