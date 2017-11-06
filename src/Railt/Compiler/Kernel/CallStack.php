<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Kernel;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Class CallStack
 */
class CallStack implements Arrayable, Renderable, Jsonable, \JsonSerializable
{
    /**
     * @var array|Definition[]
     */
    private $stack = [];

    /**
     * @param Definition $definition
     * @return CallStack
     */
    public function push(Definition $definition): CallStack
    {
        $this->stack[] = $definition;

        return $this;
    }

    /**
     * @return mixed|Definition
     */
    public function pop(): Definition
    {
        return \array_pop($this->stack);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->stack as $i => $definition) {
            if ($i + 1 === \count($this->stack)) { continue; }

            array_unshift($result, $this->getDefinitionInfo($definition));
        }

        return $result;
    }

    /**
     * @param Definition $definition
     * @return array
     */
    private function getDefinitionInfo(Definition $definition): array
    {
        $file = $definition->getDocument()->getFile();

        $name = $this->definitionToString($definition);

        if ($definition instanceof DependentDefinition) {
            $name = $this->definitionToString($definition->getParent()) . '::' . $name;
        }

        return [
            'type'   => $name,
            'file'   => $file->getPathname(),
            'line'   => $definition->getDeclarationLine() + (
                $file->isFile() ? 0 : ($file->getDefinitionLine() - 1)
            ),
            'column' => $definition->getDeclarationColumn(),
        ];
    }

    /**
     * @param Definition $definition
     * @return string
     */
    private function definitionToString(Definition $definition): string
    {
        $result = '"' . $definition->getName() . '"';

        if ($definition instanceof TypeDefinition) {
            return $definition->getTypeName() . '(' . $result . ')';
        }

        return \class_basename($definition) . '(' . $result . ')';
    }

    /**
     * @return array
     */
    public function getLastDefinitionInfo(): array
    {
        return $this->getDefinitionInfo(\array_last($this->stack));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $result = [];

        foreach ($this->toArray() as $i => $stack) {
            $result[] = \sprintf('#%d %s in %s:%s:%s',
                $i,
                $stack['type'],
                $stack['file'],
                $stack['line'],
                $stack['column']
            );
        }

        return \implode("\n", $result);
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function jsonSerialize(): string
    {
        return $this->toJson();
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return \json_encode($this->toArray(), $options);
    }
}
