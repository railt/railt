<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

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
     * Header constructor.
     */
    public function __construct()
    {
        $this->table = new \SplStack();
    }

    /**
     * @param Record $record
     * @return SymbolTable
     */
    public function register(Record $record): self
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
