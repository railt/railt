<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Railt\Compiler\Grammar\Parsers\Pragmas;
use Railt\Compiler\Grammar\Parsers\SkippedTokenDefinitions;
use Railt\Compiler\Grammar\Parsers\TokenDefinitions;
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
     * Parser constructor.
     * @param Readable $readable
     */
    public function __construct(Readable $readable)
    {
        $this->parse($readable);
    }

    /**
     * @param Readable $grammar
     * @return Reader
     */
    private function parse(Readable $grammar): Reader
    {
        $ruleName  = null;
        $ruleValue = '';

        foreach ($this->read($grammar) as $line) {
            switch ($line[0]) {
                case '%':
                    $this->parseDefinition($line);
                    break;

                case ' ':
                    $ruleValue              .= ' ' . \trim($line);
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
     * @param Readable $grammar
     * @return iterable
     */
    private function read(Readable $grammar): iterable
    {
        foreach (\explode("\n", $grammar->getContents()) as $line) {
            if ($line && ! $this->isCommentedLine(\ltrim($line))) {
                yield \rtrim($line);
            }
        }
    }

    /**
     * @param string $line
     * @return bool
     */
    private function isCommentedLine(string $line): bool
    {
        return $line{0} === '/' && $line{1} === '/';
    }

    /**
     * @param string $line
     * @throws \LogicException
     */
    private function parseDefinition(string $line): void
    {
        switch (true) {
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
