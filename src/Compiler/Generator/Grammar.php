<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Generator\Grammar\GrammarDefinition;
use Railt\Compiler\Generator\Grammar\Reader;
use Railt\Io\Readable;

/**
 * Class Grammar
 */
class Grammar implements GrammarDefinition
{
    /**
     * @var iterable
     */
    private $tokens;

    /**
     * @var iterable
     */
    private $rules;

    /**
     * @var iterable
     */
    private $pragmas;

    /**
     * Grammar constructor.
     * @param iterable $tokens
     * @param iterable $rules
     * @param Pragma $pragmas
     */
    public function __construct(iterable $tokens = [], iterable $rules = [], Pragma $pragmas)
    {
        $this->tokens  = $tokens;
        $this->rules   = $rules;
        $this->pragmas = $pragmas;
    }

    /**
     * @param Readable $grammar
     * @return static
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    public static function read(Readable $grammar)
    {
        $reader = new Reader($grammar);

        return new static(
            $reader->getTokenDefinitions(),
            $reader->getRuleDefinitions(),
            $reader->getPragmaDefinitions()
        );
    }

    /**
     * @return iterable
     */
    public function getTokenDefinitions(): iterable
    {
        return $this->tokens;
    }

    /**
     * @return iterable
     */
    public function getRuleDefinitions(): iterable
    {
        return $this->rules;
    }

    /**
     * @return Pragma
     */
    public function getPragmaDefinitions(): Pragma
    {
        return $this->pragmas;
    }
}
