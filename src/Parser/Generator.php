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
    private const BASE_PARSER_CLASS_NAME = Parser::class;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $namespace;

    /**
     * Generator constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string $namespace
     * @return Generator
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Save in-memory parser to PHP code.
     * The generated PHP code will load the same in-memory parser. The state
     * will be reset. The parser will be saved as a class, named after
     * `$className`. To retrieve the parser, one must instanciate this class.
     *
     * @param string $className Parser classname.
     * @return  string
     */
    public function generate(string $className): string
    {
        $out        = null;
        $outTokens  = null;
        $outRules   = null;
        $outPragmas = null;
        $outExtra   = null;

        $escapeRuleName = function ($ruleName) {
            if (true == $this->parser->getRule($ruleName)->isTransitional()) {
                return $ruleName;
            }

            return '\'' . $ruleName . '\'';
        };

        foreach ($this->parser->getTokens() as $namespace => $tokens) {
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

        foreach ($this->parser->getRules() as $rule) {
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

        foreach ($this->parser->getPragmas() as $pragmaName => $pragmaValue) {
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
            'class ' . $className . ' extends \\' . self::BASE_PARSER_CLASS_NAME . "\n" .
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

    /**
     * @return string
     */
    private function getNamespace(): string
    {
        return $this->namespace
            ? 'namespace ' . $this->namespace . ';' . "\n\n"
            : '';
    }

    /**
     * @return string
     */
    private function getHeader(): string
    {
        return '<?php' . "\n" .
                '/**' . "\n" .
                ' * This is generated file.' . "\n" .
                ' * Do not update it manually.' . "\n" .
                ' * Generated at ' . \date('d-m-Y H:i:s') . "\n" .
                ' */' . "\n" .
            'declare(strict_types=1);' . "\n\n";
    }

    /**
     * @param string $filePath
     * @param string $class
     * @return string
     */
    private function fileName(string $filePath, string $class): string
    {
        return $filePath . '/' . $class . '.php';
    }

    /**
     * @param string $className
     * @param string $filePath
     * @return void
     */
    public function saveTo(string $className, string $filePath): void
    {
        $sources = $this->getHeader() .
            $this->getNamespace() .
            $this->generate($className);

        \file_put_contents($this->fileName($filePath, $className), $sources);
    }
}
