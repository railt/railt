<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Llk;

use Railt\Parser\Exception\Exception;
use Railt\Parser\Grammar\Reader;
use Railt\Parser\Io\Readable;
use Railt\Parser\Llk\Rule\Analyzer;
use Railt\Parser\Llk\Rule\Repetition;
use Railt\Parser\Llk\Rule\Token;

/**
 * Class \Railt\Parser\Llk.
 *
 * This class provides a set of static helpers to manipulate (load and save) a
 * compiler more easily.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class Llk
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Llk constructor.
     * @param Readable $grammar
     */
    public function __construct(Readable $grammar)
    {
        $this->reader = new Reader($grammar);
    }

    /**
     * @return Analyzer
     * @throws \LogicException
     */
    public function getAnalyzer(): Analyzer
    {
        return new Analyzer($this->reader->getTokens());
    }

    /**
     * Load in-memory parser from a grammar description file.
     * The grammar description language is PP. See
     * `hoa://Library/Compiler/Llk/Llk.pp` for an example, or the documentation.
     *
     * @return Parser
     * @throws \LogicException
     * @throws Exception
     */
    public function getParser(): Parser
    {
        $rules  = $this->getAnalyzer()->analyzeRules($this->reader->getRules());

        return new Parser($this->reader->getTokens(), $rules, $this->reader->getPragma());
    }

    /**
     * Save in-memory parser to PHP code.
     * The generated PHP code will load the same in-memory parser. The state
     * will be reset. The parser will be saved as a class, named after
     * `$className`. To retrieve the parser, one must instanciate this class.
     *
     * @param \Railt\Parser\Llk\Parser $parser Parser to save.
     * @param string $className Parser classname.
     * @return string
     */
    public static function save(Parser $parser, $className)
    {
        $out        = null;
        $outTokens  = null;
        $outRules   = null;
        $outPragmas = null;
        $outExtra   = null;

        $escapeRuleName = function ($ruleName) use ($parser) {
            if (true === $parser->getRule($ruleName)->isTransitional()) {
                return $ruleName;
            }

            return '\'' . $ruleName . '\'';
        };

        foreach ($parser->getTokens() as $namespace => $tokens) {
            $outTokens .= '                \'' . $namespace . '\' => [' . "\n";

            foreach ($tokens as $tokenName => $tokenValue) {
                $outTokens .=
                    '                    \'' . $tokenName . '\' => \'' .
                    \str_replace(
                        ['\'', '\\\\'],
                        ['\\\'', '\\\\\\'],
                        $tokenValue
                    ) . '\',' . "\n";
            }

            $outTokens .= '                ],' . "\n";
        }

        foreach ($parser->getRules() as $rule) {
            $arguments = [];

            // Name.
            $arguments['name'] = $escapeRuleName($rule->getName());

            if ($rule instanceof Token) {
                // Token name.
                $arguments['tokenName'] = '\'' . $rule->getTokenName() . '\'';
            } else {
                if ($rule instanceof Repetition) {
                    // Minimum.
                    $arguments['min'] = $rule->getMin();

                    // Maximum.
                    $arguments['max'] = $rule->getMax();
                }

                // Children.
                $ruleChildren = $rule->getChildren();

                if (null === $ruleChildren) {
                    $arguments['children'] = 'null';
                } elseif (false === \is_array($ruleChildren)) {
                    $arguments['children'] = $escapeRuleName($ruleChildren);
                } else {
                    $arguments['children'] =
                        '[' .
                        \implode(', ', \array_map($escapeRuleName, $ruleChildren)) .
                        ']';
                }
            }

            // Node ID.
            $nodeId = $rule->getNodeId();

            if (null === $nodeId) {
                $arguments['nodeId'] = 'null';
            } else {
                $arguments['nodeId'] = '\'' . $nodeId . '\'';
            }

            if ($rule instanceof Token) {
                // Unification.
                $arguments['unification'] = $rule->getUnificationIndex();

                // Kept.
                $arguments['kept'] = $rule->isKept() ? 'true' : 'false';
            }

            // Default node ID.
            if (null !== $defaultNodeId = $rule->getDefaultId()) {
                $defaultNodeOptions = $rule->getDefaultOptions();

                if (! empty($defaultNodeOptions)) {
                    $defaultNodeId .= ':' . \implode('', $defaultNodeOptions);
                }

                $outExtra .=
                    "\n" .
                    '        $this->getRule(' . $arguments['name'] . ')->setDefaultId(' .
                        '\'' . $defaultNodeId . '\'' .
                    ');';
            }

            // PP representation.
            if (null !== $ppRepresentation = $rule->getPPRepresentation()) {
                $outExtra .=
                    "\n" .
                    '        $this->getRule(' . $arguments['name'] . ')->setPPRepresentation(' .
                        '\'' . \str_replace('\'', '\\\'', $ppRepresentation) . '\'' .
                    ');';
            }

            $outRules .=
                "\n" .
                '                ' . $arguments['name'] . ' => new \\' . \get_class($rule) . '(' .
                \implode(', ', $arguments) .
                '),';
        }

        foreach ($parser->getPragmas() as $pragmaName => $pragmaValue) {
            $outPragmas .=
                "\n" .
                '                \'' . $pragmaName . '\' => ' .
                (\is_bool($pragmaValue)
                    ? (true === $pragmaValue ? 'true' : 'false')
                    : (\is_int($pragmaValue)
                        ? $pragmaValue
                        : '\'' . $pragmaValue . '\'')) .
                ',';
        }

        $out .=
            'class ' . $className . ' extends \Railt\Parser\Llk\Parser' . "\n" .
            '{' . "\n" .
            '    public function __construct()' . "\n" .
            '    {' . "\n" .
            '        parent::__construct(' . "\n" .
            '            [' . "\n" .
            $outTokens .
            '            ],' . "\n" .
            '            [' .
            $outRules . "\n" .
            '            ],' . "\n" .
            '            [' .
            $outPragmas . "\n" .
            '            ]' . "\n" .
            '        );' . "\n" .
            $outExtra . "\n" .
            '    }' . "\n" .
            '}' . "\n";

        return $out;
    }
}
