<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Laminas\Code\Exception\InvalidArgumentException;
use Laminas\Code\Generator\Exception\RuntimeException;
use Laminas\Code\Generator\ValueGenerator;
use Phplrt\Compiler\Analyzer;
use Phplrt\Compiler\Compiler;
use Phplrt\Contracts\Grammar\RuleInterface;
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;
use Phplrt\Source\File;

/**
 * Class Generator
 */
class Generator
{
    /**
     * @var string
     */
    private const GRAMMAR_PATHNAME = __DIR__ . '/../../resources/grammar/grammar.pp2';

    /**
     * @var string
     */
    private const GRAMMAR_TEMPLATE_PATHNAME = __DIR__ . '/../../resources/templates/grammar.tpl.php';

    /**
     * @var Analyzer
     */
    private Analyzer $analyzer;

    /**
     * Generator constructor.
     *
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->analyzer = $this->bootAnalyzer();
    }


    /**
     * @return Analyzer
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    private function bootAnalyzer(): Analyzer
    {
        $compiler = new Compiler();
        $compiler->load(File::fromPathname(self::GRAMMAR_PATHNAME));

        return $compiler->getAnalyzer();
    }

    /**
     * @return void
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function generateAndSave(): void
    {
        \file_put_contents(__DIR__ . '/grammar.php', $this->generate());
    }

    /**
     * @return string
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function generate(): string
    {
        return $this->render(self::GRAMMAR_TEMPLATE_PATHNAME, [
            'initial'  => $this->value($this->analyzer->initial),
            'lexemes'  => $this->value($this->analyzer->tokens[Analyzer::STATE_DEFAULT]),
            'skips'    => $this->value($this->analyzer->skip),
            'grammar'  => $this->getRulesString(),
            'reducers' => $this->getReducersString(),
        ]);
    }

    /**
     * @param string $pathname
     * @param array $variables
     * @return string
     */
    private function render(string $pathname, array $variables = []): string
    {
        \extract($variables, \EXTR_OVERWRITE);

        return require $pathname;
    }

    /**
     * @param mixed $value
     * @param int $depth
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    private function value($value, int $depth = 1): string
    {
        $generator = new ValueGenerator($value);
        $generator->setArrayDepth($depth);

        return $generator->generate();
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    private function getRulesString(): string
    {
        $result = [];

        foreach ($this->analyzer->rules as $index => $rule) {
            $value = \vsprintf('new \\%s(%s)', [
                \get_class($rule),
                \implode(', ', $this->getRuleArguments($rule)),
            ]);

            $result[] = $this->value($index) . ' => ' . $value;
        }

        return $this->formatArray($result);
    }

    /**
     * @param RuleInterface $rule
     * @return array
     * @throws RuntimeException
     */
    private function getRuleArguments(RuleInterface $rule): array
    {
        $result = [];

        foreach ($rule->getConstructorArguments() as $arg) {
            $result[] = $this->value($arg, 2);
        }

        return $result;
    }

    /**
     * @param array $result
     * @return string
     */
    private function formatArray(array $result): string
    {
        return \vsprintf('[%s%s%s]', [
            "\n        ",
            \implode(",\n        ", $result),
            "\n    ",
        ]);
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    private function getReducersString(): string
    {
        $result = [];

        foreach ($this->analyzer->reducers as $state => $rule) {
            $lines = \explode("\n", $rule);

            foreach ($lines as $index => &$line) {
                $line = $line ? \str_repeat(' ', $index ? 8 : 12) . $line : '';
            }

            $template = " => static function (\$children) {\n%s\n        }";

            $result[] = $this->value($state) . \vsprintf($template, [
                    \implode("\n", $lines),
                ]);
        }

        return $this->formatArray($result);
    }
}
