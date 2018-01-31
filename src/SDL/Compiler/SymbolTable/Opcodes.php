<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Illuminate\Support\Str;
use Railt\SDL\Compiler\SymbolTable;
use Zend\Code\Generator\ValueGenerator;

/**
 * Class Opcodes
 */
class Opcodes
{
    /**
     * @var SymbolTable
     */
    private $table;

    /**
     * Opcodes constructor.
     * @param SymbolTable $table
     */
    public function __construct(SymbolTable $table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    public function toString(): string
    {
        $result    = [];
        $pattern   = '%6s %-10s | %-30s %-30s';
        $delimiter = \vsprintf($pattern, [
            \str_repeat('-', 6),
            \str_repeat('-', 10),
            \str_repeat('-', 30),
            \str_repeat('-', 30),
        ]);

        $result[] = $delimiter;
        $result[] = \sprintf($pattern, 'ID', 'OFFSET', 'OP', 'OPERANDS');
        $result[] = $delimiter;

        foreach ($this->table->getIterator() as $i => $record) {
            if (($i + 1 % 20) === 0) {
                $result[] = $delimiter;
            }

            $result[] = \vsprintf($pattern, [
                $i,
                $this->stringifyRecordOffset($record),
                $this->stringifyRecordCommand($record),
                $this->stringifyRecordName($record),
            ]);
        }

        return \implode("\n", $result);
    }

    /**
     * @param Record $record
     * @return string
     */
    private function stringifyRecordOffset(Record $record): string
    {
        $offset = \dechex($record->getOffset());

        return '0x' . \str_pad($offset, 8, '0', \STR_PAD_LEFT);
    }

    /**
     * @param Record $record
     * @return string
     */
    private function stringifyRecordCommand(Record $record): string
    {
        return 'CREATE_TYPE_' . Str::upper(Str::snake($record->getType()));
    }

    /**
     * @param Record $record
     * @return string
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    private function stringifyRecordName(Record $record): string
    {
        $generator = new ValueGenerator($record->getName(), ValueGenerator::TYPE_STRING);

        return $generator->generate();
    }
}
