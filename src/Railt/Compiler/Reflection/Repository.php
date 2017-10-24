<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Reflection\Base\Definitions\BaseDefinition;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Reflection\Contracts\Processable\ProcessableDefinition;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Exceptions\TypeRedefinitionException;
use Traversable;

/**
 * Class Repository
 */
class Repository implements Dictionary, \Countable, \IteratorAggregate
{
    use Support;

    /**
     * Pattern for unique type override exception messages.
     */
    private const REDEFINITION_UNIQUE_TYPE_ERROR = 'Cannot declare %s, because the name already in use in %s';

    /**
     * Pattern for unique type override exception messages.
     */
    private const REDEFINITION_TYPE_ERROR = 'Cannot declare %s, because the definition already registered in %s';

    /**
     * A set of types where the key is the Type name in the format:
     *
     * <code>
     *  [
     *      ...
     *      {TypeName} => Definition::class,
     *      ...
     *  ]
     * </code>
     *
     * @var array|Definition[]
     */
    private $storage = [];

    /**
     * @return Traversable|Definition[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator(\array_values($this->storage));
    }

    /**
     * @param Definition $type
     * @param bool $force
     * @return Dictionary
     * @throws TypeRedefinitionException
     */
    public function register(Definition $type, bool $force = false): Dictionary
    {
        if ($force === false) {
            $this->verifyTypeIsRegistered($type);
        }

        $this->storage[$type->getName()] = $type;

        return $this;
    }

    /**
     * @param Definition $type
     * @return void
     * @throws TypeRedefinitionException
     */
    private function verifyTypeIsRegistered(Definition $type): void
    {
        if ($this->has($type->getName())) {
            $error = \sprintf(self::REDEFINITION_UNIQUE_TYPE_ERROR,
                $this->typeToString($type),
                $this->typeToString($type->getDocument())
            );

            throw new TypeRedefinitionException($error);
        }
    }

    /**
     * @param string $name
     * @param Document|null $document
     * @return Definition
     * @throws TypeNotFoundException
     */
    public function get(string $name, Document $document = null): Definition
    {
        if ($this->has($name, $document)) {
            return $this->storage[$name];
        }

        $error = $document === null
            ? \sprintf('Type "%s" not found', $name)
            : \sprintf('Document "%s" does not contain the "%s" type', $this->typeToString($document), $name);

        throw new TypeNotFoundException($error);
    }

    /**
     * @param Document|null $document
     * @return array
     */
    public function all(Document $document = null): array
    {
        $result = [];

        foreach ($this->storage as $name => $definition) {
            if ($this->isContainedInDocument($definition, $document)) {
                $result[] = $definition;
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @param Document|null $document
     * @return bool
     */
    public function has(string $name, Document $document = null): bool
    {
        $definition = $this->storage[$name] ?? null;

        return $definition !== null && $this->isContainedInDocument($definition, $document);
    }

    /**
     * @param Definition $definition
     * @param Document|null $document
     * @return bool
     */
    private function isContainedInDocument(Definition $definition, Document $document = null): bool
    {
        return $document === null || $document->getUniqueId() === $definition->getDocument()->getUniqueId();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->storage);
    }
}
