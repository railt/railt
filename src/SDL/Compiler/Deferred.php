<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Reflection\Contracts\Extensions\TypeExtension;
use Railt\Reflection\Contracts\Invocations\TypeInvocation;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class Deferred
 */
class Deferred
{
    /**
     * @var \SplStack|TypeExtension[]
     */
    private $extensions;

    /**
     * @var \SplStack|TypeInvocation[]
     */
    private $invocations;

    /**
     * Deferred constructor.
     */
    public function __construct()
    {
        $this->extensions  = new \SplStack();
        $this->invocations = new \SplStack();
    }

    /**
     * @param Record $record
     * @return void
     */
    public function invocation(Record $record): void
    {
        $this->invocations->push($record);
    }

    /**
     * @param Record $record
     * @return void
     */
    public function extension(Record $record): void
    {
        $this->extensions->push($record);
    }

    /**
     * @param Pipeline $pipeline
     * @return void
     */
    public function resolve(Pipeline $pipeline): void
    {
        while ($this->extensions->count() > 0) {
            $pipeline->make($this->extensions->pop());
        }

        while ($this->invocations->count() > 0) {
            $pipeline->make($this->invocations->pop());
        }
    }
}
