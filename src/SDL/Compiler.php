<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Builder;

/**
 * Class Compiler
 */
class Compiler
{

    /**
     * @param Readable $readable
     * @return SymbolTable
     * @throws \Railt\Compiler\Exception\UnexpectedTokenException
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     * @throws \Railt\Compiler\Exception\Exception
     * @throws \LogicException
     */
    private function prepare(Readable $readable): SymbolTable
    {
        $builder = new Builder();
    }

    /**
     * @param Readable $readable
     * @return mixed|null
     * @throws \Railt\Compiler\Exception\UnexpectedTokenException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     * @throws \Railt\Compiler\Exception\Exception
     * @throws \LogicException
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    public function compile(Readable $readable)
    {
        $table = $this->prepare($readable);
        dd($table);
    }
}
