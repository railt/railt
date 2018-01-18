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
use Railt\Reflection\Support;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Exceptions\TypeNotFoundException;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Runtime\CallStack;

/**
 * Class Repository
 */
class Repository implements Dictionary, \Countable, \IteratorAggregate
{
    use Support;

    /**
     * @var array|TypeDefinition[]
     */
    private $definitions = [];

    /**
     * @var CallStack
     */
    protected $stack;

    /**
     * Repository constructor.
     * @param CallStack $stack
     */
    public function __construct(CallStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @param TypeDefinition $type
     * @param bool $force
     * @return Dictionary
     * @throws TypeConflictException
     */
    public function register(TypeDefinition $type, bool $force = false): Dictionary
    {
        if (! $force && $this->has($type->getName())) {
            $error = \sprintf(
                'Can not declare %s, because the name %s already in use',
                $this->typeToString($type),
                $type->getName()
            );

            throw new TypeConflictException($error, $this->stack);
        }

        $this->definitions[$type->getName()] = $type;

        return $this;
    }

    /**
     * @param string $name
     * @param TypeDefinition $from
     * @return TypeDefinition
     */
    public function get(string $name, TypeDefinition $from): TypeDefinition
    {
        if ($this->has($name)) {
            $result = $this->definitions[$name];

            if ($result instanceof Compilable) {
                $result->compile();
            }

            return $result;
        }

        $error = \sprintf('Type "%s" not found', $name);
        throw new TypeNotFoundException($error, $this->stack);
    }

    /**
     * @param string $type
     * @return iterable|TypeDefinition[]
     */
    public function only(string $type): iterable
    {
        foreach ($this->definitions as $definition) {
            if ($definition instanceof $type) {
                yield $definition;
            }
        }
    }

    /**
     * @return iterable|TypeDefinition[]
     */
    public function all(): iterable
    {
        yield from $this->getIterator();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->definitions);
    }

    /**
     * @return \Traversable|TypeDefinition[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator(\array_values($this->definitions));
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->definitions);
    }
}
