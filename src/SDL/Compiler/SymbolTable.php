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
 * Class SymbolTable
 */
class SymbolTable
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
}
