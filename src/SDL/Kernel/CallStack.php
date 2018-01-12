<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Kernel;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Support;

/**
 * Class CallStack
 */
class CallStack implements Arrayable, Renderable, Jsonable, \JsonSerializable, \Countable
{
    use Support;

    public const EVENT_PUSH = 'push';
    public const EVENT_POP  = 'pop';

    /**
     * @var array|Definition[]
     */
    private $stack = [];

    /**
     * @var array|\Closure[]
     */
    private $subscribers = [];

    /**
     * @param Definition[] ...$definitions
     * @return CallStack
     */
    public function push(Definition ...$definitions): self
    {
        foreach ($definitions as $definition) {
            $this->stack[] = $definition;
        }

        $this->fire(static::EVENT_PUSH, ...$definitions);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return CallStack
     */
    public function listen(\Closure $then): self
    {
        $this->subscribers[] = $then;

        return $this;
    }

    /**
     * @param string $event
     * @param Definition[] ...$definitions
     * @return void
     */
    private function fire(string $event, Definition ...$definitions): void
    {
        foreach ($definitions as $definition) {
            foreach ($this->subscribers as $subscriber) {
                $subscriber($event, $definition);
            }
        }
    }

    /**
     * @param Definition[] ...$definitions
     * @return Transaction
     */
    public function transaction(Definition ...$definitions): Transaction
    {
        return (new Transaction($this))->push(...$definitions);
    }

    /**
     * @param int $size
     * @return CallStack
     */
    public function pop(int $size = 1): self
    {
        for ($i = 0; $i < $size; ++$i) {
            $definition = \array_pop($this->stack);
            $this->fire(static::EVENT_POP, $definition);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->stack as $i => $definition) {
            \array_unshift($result, $this->getDefinitionInfo($definition));
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

        $name = $this->typeToString($definition);

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
     * @return array
     */
    public function getLastDefinitionInfo(): array
    {
        $last = \array_last($this->stack);

        if ($last !== null) {
            return $this->getDefinitionInfo($last);
        }

        return [];
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
            $result[] = \sprintf('#%d %s(%s): %s',
                $i,
                $stack['file'],
                $stack['line'],
                $stack['type']
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

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->stack);
    }
}
