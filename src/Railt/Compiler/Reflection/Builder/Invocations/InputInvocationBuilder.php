<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Invocations;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Invocations\BaseInputInvocation;
use Railt\Reflection\Contracts\Definitions\InputDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;

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
     * InputInvocationBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param TypeDefinition $parent
     * @param array $path
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, TypeDefinition $parent, array $path)
    {
        $this->path   = $path;
        $this->parent = $parent;
        $this->name   = $parent->getName();
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function onCompile(TreeNode $ast): bool
    {
        $key   = (string)$ast->getChild(0)->getChild(0)->getValueValue();
        $value = $ast->getChild(1)->getChild(0);

        $this->arguments[$key] = $this->parseValue($value, $this->parent, \array_merge($this->path, [$key]));

        return true;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'path',
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

        return \array_reduce($this->path, $reduce, $this->parent->getTypeDefinition());
    }
}
