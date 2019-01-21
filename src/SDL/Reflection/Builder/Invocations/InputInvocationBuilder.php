<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Invocations;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Base\Invocations\BaseInputInvocation;
use Railt\SDL\Contracts\Definitions\InputDefinition;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

/**
 * Class InputInvocationBuilder
 * @property ArgumentDefinition $parent
 */
class InputInvocationBuilder extends BaseInputInvocation implements Compilable
{
    use Compiler;

    /**
     * @var array
     */
    protected $path = [];

    /**
     * @var string
     */
    protected $parentType;

    /**
     * InputInvocationBuilder constructor.
     *
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @param string $parentType
     * @param array $path
     * @throws \OutOfBoundsException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document, string $parentType, array $path)
    {
        $this->path = $path;
        $this->parentType = $parentType;
        $this->boot($ast, $document);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->parentType;
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        $key = (string)$ast->getChild(0)->getChild(0)->getValue();
        $value = $ast->getChild(1)->getChild(0);

        $this->arguments[$key] = $this->parseValue($value, $this->parentType, \array_merge($this->path, [$key]));

        return true;
    }

    /**
     * @return TypeDefinition
     */
    public function getParent(): TypeDefinition
    {
        return $this->load($this->parentType);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'path',
            'parentType',
        ]);
    }

    /**
     * @return null|TypeDefinition
     */
    public function getTypeDefinition(): ?TypeDefinition
    {
        $reduce = function (?InputDefinition $carry, $item): ?TypeDefinition {
            /** @var ArgumentDefinition|null $argument */
            $argument = $carry->getArgument($item);
            // TODO $argument can be null. Add validation?

            return $argument ? $argument->getTypeDefinition() : null;
        };

        return \array_reduce($this->path, $reduce, $this->getParent());
    }
}
