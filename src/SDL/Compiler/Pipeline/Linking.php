<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

use Railt\Io\Readable;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\Runtime\CallStackInterface;

/**
 * Class Linking
 */
class Linking implements Stage
{
    /**
     * @var SymbolTable
     */
    private $linker;

    /**
     * Linking constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->linker = new SymbolTable($stack);
    }

    /**
     * @param Readable $input
     * @param mixed $ast
     * @return mixed|\Railt\SDL\Compiler\SymbolTable
     * @throws \LogicException
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    public function handle(Readable $input, $ast)
    {
        return $this->linker->build($input, $ast);
    }
}
