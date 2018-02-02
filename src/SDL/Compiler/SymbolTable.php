<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Io\Readable;
use Railt\SDL\Compiler\SymbolTable\Link;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * The Symbol Table contains a list of all type
 * names and their ASTs for their subsequent construction
 * in the form of an object model.
 */
class SymbolTable implements \IteratorAggregate
{
    /**
     * @var \SplStack|Record[]
     */
    private $table;

    /**
     * @var string
     */
    private $namespace = '';

    /**
     * @var array|string[]
     */
    private $aliases = [];

    /**
     * @var \SplStack
     */
    private $links;

    /**
     * @var Readable
     */
    private $input;

    /**
     * Header constructor.
     */
    public function __construct(Readable $input)
    {
        $this->input = $input;
        $this->table = new \SplStack();
        $this->links = new \SplStack();
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        \assert($this->namespace === '');

        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $original
     * @param string $alias
     * @return void
     */
    public function addAlias(string $original, string $alias): void
    {
        throw new \LogicException('Not implemented');
    }

    /**
     * @param string $type
     * @param string $module
     * @return void
     */
    public function addLink(string $type, string $module): void
    {
        $this->links[] = new Link($this->input, $type, $module);
    }

    /**
     * @param Record $record
     * @return SymbolTable
     */
    public function addRecord(Record $record): self
    {
        $this->table->push($record);

        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'table' => $this->table,
        ];
    }

    /**
     * @return \Traversable|Record[]
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->table as $record) {
            yield $record;
        }
    }
}
