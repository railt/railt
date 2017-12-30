<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Grammar;

use Railt\Parser\Io\Readable;

/**
 * Class Reader
 */
class Reader
{
    private const DEFAULT_NAMESPACE = 'default';

    private const T_COMMENT_PREFIX = '//';

    private const T_TOKEN_PREFIX     = '%token';
    private const T_TOKEN_PREFIX_LEN = 6;

    private const T_SKIPPED_TOKEN_PREFIX     = '%skip';
    private const T_SKIPPED_TOKEN_PREFIX_lEN = 5;
    private const T_SKIPPED_TOKEN_NAME       = 'skip';

    private const T_PRAGMA_PREFIX     = '%pragma';
    private const T_PRAGMA_PREFIX_LEN = 7;

    /**
     * @var Readable
     */
    private $input;

    /**
     * @var bool
     */
    private $parsed = false;

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
    private $pragma = [];

    /**
     * @var array
     */
    private $skipped = [];

    /**
     * Parser constructor.
     * @param Readable $readable
     */
    public function __construct(Readable $readable)
    {
        $this->input = $readable;
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getTokens(): array
    {
        return $this->parsed()->tokens;
    }

    /**
     * @return Reader
     * @throws \LogicException
     */
    private function parsed(): self
    {
        if ($this->parsed === false) {
            $ruleName  = null;
            $ruleValue = '';

            foreach ($this->lines() as $line) {
                switch ($line[0]) {
                    case '%':
                        $this->parseDefinition($line);
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

            foreach ($this->skipped as $namespace => $token) {
                if (\array_key_exists($namespace, $this->tokens)) {
                    $this->tokens[$namespace][self::T_SKIPPED_TOKEN_NAME] = $token;
                }
            }

            $this->parsed = true;
        }

        return $this;
    }

    /**
     * @param string $line
     * @throws \LogicException
     */
    private function parseDefinition(string $line): void
    {
        switch (true) {
            case $this->startsWith($line, self::T_PRAGMA_PREFIX):
                $this->parsePragma($this->escape($line, self::T_PRAGMA_PREFIX_LEN));
                break;


            case $this->startsWith($line, self::T_TOKEN_PREFIX):
                $this->parseToken($this->escape($line, self::T_TOKEN_PREFIX_LEN));
                break;

            case $this->startsWith($line, self::T_SKIPPED_TOKEN_PREFIX):
                $this->parseSkipped($this->escape($line, self::T_SKIPPED_TOKEN_PREFIX_lEN));
                break;
        }
    }

    /**
     * @return iterable
     */
    private function lines(): iterable
    {
        $lines = \explode("\n", $this->input->getContents());

        foreach ($lines as $line) {
            if (! $line || $this->startsWith($line, self::T_COMMENT_PREFIX)) {
                continue;
            }

            yield \rtrim($line);
        }
    }

    /**
     * @param string $line
     * @param string $prefix
     * @return bool
     */
    private function startsWith(string $line, string $prefix): bool
    {
        return \strpos($line, $prefix) === 0;
    }

    /**
     * @param string $pragma
     * @return void
     * @throws \LogicException
     */
    private function parsePragma(string $pragma): void
    {
        // [1 => name, 2 => value]
        $valid = \preg_match('/^([^\h]+)\h+(.*)$/u', $pragma, $matches);

        if ($valid === 0) {
            throw new \LogicException('Invalid pragma definition format in "' . $pragma . '"');
        }

        switch ($matches[2]) {
            case 'true':
                $this->pragma[$matches[1]] = true;
                break;
            case 'false':
                $this->pragma[$matches[1]] = false;
                break;
            default:
                $this->pragma[$matches[1]] = $matches[2];
        }
    }

    /**
     * @param string $line
     * @param int $len
     * @return string
     */
    private function escape(string $line, int $len): string
    {
        return \ltrim(\substr($line, $len));
    }

    /**
     * @param string $token
     * @throws \LogicException
     */
    private function parseToken(string $token): void
    {
        // [1 => namespace, 2 => name, 3 => body, 4 => namespace]
        $valid = \preg_match('/^(?:([^:]+):)?([^\h]+)\h+(.*?)(?:\h+->\h+(.*))?$/u', $token, $matches);

        if ($valid === 0) {
            throw new \LogicException('Invalid token definition format in "' . $token . '"');
        }

        $namespace = $matches[1] ?: self::DEFAULT_NAMESPACE;

        if (! \array_key_exists($namespace, $this->tokens)) {
            $this->tokens[$namespace] = [];
        }

        $this->tokens[$namespace][$matches[2] . (isset($matches[4]) ? ':' . $matches[4] : '')] = $matches[3];
    }

    /**
     * @param string $skip
     * @throws \LogicException
     */
    private function parseSkipped(string $skip): void
    {
        // [1 => namespace, 2 => name, 3 => body]
        $valid = \preg_match('/^(?:([^:]+):)?([^\h]+)\h+(.*)$/u', $skip, $matches);

        if ($valid === 0) {
            throw new \LogicException('Invalid skipped token definition format in "' . $skip . '"');
        }

        $namespace = $matches[1] ?: self::DEFAULT_NAMESPACE;

        $this->skipped[$namespace] = \array_key_exists($namespace, $this->skipped)
            ? '(?:' . $this->skipped[$namespace] . '|' . $matches[3] . ')'
            : $matches[3];
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getRules(): array
    {
        return $this->parsed()->rules;
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function getPragma(): array
    {
        return $this->parsed()->pragma;
    }
}
