<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Exceptions\TypeNotFoundException;
use Railt\SDL\Runtime\CallStackInterface;

/**
 * Class Repository
 */
class Repository implements Dictionary
{
    /**
     * @var array|TypeDefinition[]
     */
    private $types = [];

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Repository constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @param string $name
     * @return string
     */
    private function index(string $name): string
    {
        return $name;
    }

    /**
     * @param string $name
     * @param TypeDefinition|null $from
     * @return TypeDefinition
     * @throws TypeNotFoundException
     */
    public function get(string $name, TypeDefinition $from = null): TypeDefinition
    {
        if (!$this->has($name)) {
            $this->throwTypeNotFoundError($name, $from);
        }

        return $this->types[$this->index($name)];
    }

    /**
     * @param string $name
     * @param TypeDefinition|null $from
     * @return void
     * @throws TypeNotFoundException
     */
    protected function throwTypeNotFoundError(string $name, TypeDefinition $from = null): void
    {
        if ($from) {
            $this->stack->push($from);
        }

        $error = \sprintf('Type %s not found', $name);
        throw new TypeNotFoundException($error, $this->stack);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($this->index($name), $this->types);
    }

    /**
     * @param TypeDefinition $type
     * @return Dictionary
     */
    public function add(TypeDefinition $type): Dictionary
    {
        $this->types[$this->index($type->getName())] = $type;

        return $this;
    }

    /**
     * @return iterable|TypeDefinition[]
     */
    public function all(): iterable
    {
        return \array_values($this->types);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->types);
    }
}
