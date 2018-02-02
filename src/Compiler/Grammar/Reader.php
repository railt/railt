<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Illuminate\Support\Str;
use Railt\Compiler\Grammar\Parsers\Pragmas;
use Railt\Compiler\Grammar\Parsers\SkippedTokenDefinitions;
use Railt\Compiler\Grammar\Parsers\TokenDefinitions;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Grammar
 */
class Reader
{
    /**
     * @var array
     */
    private $tokens = [];

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @var array
     */
    private $pragmas = [];

    /**
     * @var array|string[]
     */
    private $includes;

    /**
     * Parser constructor.
     * @param Readable $readable
     * @throws \LogicException
     */
    public function __construct(Readable $readable)
    {
        $this->includes[$readable->getPathname()] = $readable->getContents();

        $this->parse();
    }

    /**
     * @return Reader
     * @throws \LogicException
     */
    private function parse(): self
    {
        $ruleName  = null;
        $ruleValue = '';

        foreach ($this->read() as $file => $line) {
            switch ($line[0] ?? '') {
                case '%':
                    $this->parseDefinition($file, $line);
                    break;

                case ' ':
                    $ruleValue .= ' ' . \trim($line);
                    $this->rules[$ruleName] = $ruleValue;
                    break;

                default:
                    $ruleName  = \substr($line, 0, -1);
                    $ruleValue = '';
            }
        }

        $this->pragmas = Pragmas::withDefaults($this->pragmas);

        return $this;
    }

    /**
     * @return iterable
     */
    private function read(): iterable
    {
        while (\count($this->includes) > 0) {
            [$path, $content] = $this->first($this->includes);
            \array_shift($this->includes);

            foreach (\explode("\n", $content) as $line) {
                if ($line && ! $this->isCommentedLine(\ltrim($line))) {
                    yield $path => \rtrim($line);
                }
            }
        }
    }

    /**
     * @param array $target
     * @return array
     */
    private function first(array $target): array
    {
        \reset($target);
        $key = \key($target);

        return [$key, \array_shift($target)];
    }

    /**
     * @param string $line
     * @return bool
     */
    private function isCommentedLine(string $line): bool
    {
        [$ch0, $ch1] = [$line[0] ?? '', $line[1] ?? ''];

        return $ch0 === '/' && $ch1 === '/';
    }

    /**
     * @param Readable $grammar
     * @param string $line
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     */
    private function parseDefinition(string $file, string $line): void
    {
        switch (true) {
            case $this->isInclude($line):
                $this->doInclude($file, \trim(\substr($line, 9)));
                break;

            case Pragmas::match($line):
                foreach (Pragmas::parse($line) as $name => $value) {
                    $this->pragmas[$name] = $value;
                }
                break;

            case TokenDefinitions::match($line):
                foreach (TokenDefinitions::parse($line) as $name => $value) {
                    $this->tokens[$name] = $value;
                }
                break;

            case SkippedTokenDefinitions::match($line):
                foreach (SkippedTokenDefinitions::parse($line) as $name => $value) {
                    $this->tokens[$name] = $value;
                }
                break;
        }
    }

    /**
     * @param string $rule
     * @return bool
     */
    private function isInclude(string $rule): bool
    {
        return Str::startsWith($rule, '%include');
    }

    /**
     * @param Readable $from
     * @param string $file
     * @throws \Railt\Io\Exceptions\NotReadableException
     */
    private function doInclude(string $from, string $file): void
    {
        $include = File::fromPathname(\dirname($from) . '/' . $file);

        $this->includes[$include->getPathname()] = $include->getContents();
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getPragmas(): array
    {
        return $this->pragmas;
    }
}
