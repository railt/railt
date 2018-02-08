<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Railt\Io\Readable;
use Railt\SDL\Compiler\SymbolTable\Context\Link;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Readable
     */
    private $input;

    /**
     * @var string
     */
    private $namespace = '';

    /**
     * @var array|Record[]
     */
    private $records;

    /**
     * @var \SplStack
     */
    private $links;

    /**
     * Context constructor.
     * @param Readable $input
     */
    public function __construct(Readable $input)
    {
        $this->input   = $input;
        $this->links   = new \SplStack();
        $this->records = new \SplQueue();
    }

    /**
     * @param Record $record
     */
    public function addRecord(Record $record): void
    {
        $record->setNamespace($this->getNamespace());
        $record->setFile($this->input);

        $this->records->push($record);
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
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
     * @param string $type
     * @param string $from
     */
    public function addLink(string $type, string $from): void
    {
        $this->links->push(new Link($this->input, $type, $from));
    }

    /**
     * @return \Traversable|Link[]
     */
    public function getLinks(): \Traversable
    {
        return $this->links;
    }

    /**
     * @return \Traversable|Record[]
     */
    public function getRecords(): \Traversable
    {
        return $this->records;
    }
}
