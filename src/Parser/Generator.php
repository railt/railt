<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

/**
 * Class Generator
 */
class Generator
{
    /**
     * Save in-memory parser to PHP code.
     * The generated PHP code will load the same in-memory parser. The state
     * will be reset. The parser will be saved as a class, named after
     * `$className`. To retrieve the parser, one must instanciate this class.
     *
     * @param Parser $parser Parser to save.
     * @param string $className Parser classname.
     * @return  string
     */
    public static function save(Parser $parser, string $className): string
    {
        $out        = null;
        $outTokens  = null;
        $outRules   = null;
        $outPragmas = null;
        $outExtra   = null;

        $escapeRuleName = function ($ruleName) use ($parser) {
            if (true == $parser->getRule($ruleName)->isTransitional()) {
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

            if ($rule instanceof Rule\Token) {
                // Token name.
                $arguments['tokenName'] = '\'' . $rule->getTokenName() . '\'';
            } else {
                if ($rule instanceof Rule\Repetition) {
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

            if ($rule instanceof Rule\Token) {
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
            'class ' . $className . ' extends \Railt\Parser\Parser' . "\n" .
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
