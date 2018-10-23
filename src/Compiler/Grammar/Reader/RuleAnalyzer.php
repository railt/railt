<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Reader\Analyzer\Analyzer;
use Railt\Compiler\Grammar\Reader\Analyzer\GrammarAnalyzer;
use Railt\Compiler\Grammar\Reader\Analyzer\TerminalsSimplifier;
use Railt\Compiler\LexerInterface;

/**
 * Class RuleAnalyzer
 */
class RuleAnalyzer implements Analyzer
{
    /**
     * @var array|Analyzer[]
     */
    private $analyzers;

    /**
     * Rule analysers
     */
    private const ANALYSERS = [
        GrammarAnalyzer::class,
        TerminalsSimplifier::class,
    ];

    /**
     * RuleAnalyzer constructor.
     * @param LexerInterface $lexer
     */
    public function __construct(LexerInterface $lexer)
    {
        $this->analyzers = \iterator_to_array($this->boot($lexer));
    }

    /**
     * @param LexerInterface $lexer
     * @return \Traversable
     */
    private function boot(LexerInterface $lexer): \Traversable
    {
        foreach (self::ANALYSERS as $analyzer) {
            yield new $analyzer($lexer);
        }
    }

    /**
     * @param iterable $rules
     * @return iterable
     */
    public function analyze(iterable $rules): iterable
    {
        foreach ($this->analyzers as $analyser) {
            $rules = $analyser->analyze($rules);
        }

        return $rules;
    }
}
