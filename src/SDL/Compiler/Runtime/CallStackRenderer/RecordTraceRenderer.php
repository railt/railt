<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Runtime\CallStackRenderer;

use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class RecordRenderer
 */
class RecordTraceRenderer extends BaseTraceRenderer
{
    /**
     * @var Record
     */
    private $record;

    /**
     * RecordRenderer constructor.
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        $this->record   = $record;

        $file     = $record->getFile();
        $position = $file->getPosition($record->getOffset());

        $this->file   = $file->getPathname();
        $this->line   = $position->getLine();
        $this->column = $position->getColumn();
    }

    /**
     * @return string
     */
    public function toTraceString(int $position): string
    {
        return \vsprintf('#%d %s(%d): %s("%s")', [
            $position,
            $this->getFile(),
            $this->getLine(),
            $this->record->getType(),
            $this->record->getFullyQualifiedName(),
        ]);
    }
}
