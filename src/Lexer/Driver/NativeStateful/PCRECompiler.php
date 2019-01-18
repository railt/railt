<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Driver\NativeStateful;

use Railt\Lexer\Token\Unknown;

/**
 * Class PCRECompiler
 */
class PCRECompiler
{
    /**
     * Regex delimiter
     */
    protected const REGEX_DELIMITER = '/';

    private const FLAG_UNICODE = 'u';
    private const FLAG_DOT_ALL = 's';
    private const FLAG_ANALYZED = 'S';

    /**
     * @var array|string[]
     */
    protected $tokens = [];

    /**
     * PCRECompiler constructor.
     * @param array $tokens
     */
    public function __construct(array $tokens = [])
    {
        $this->tokens = $tokens;
    }

    /**
     * @param string $token
     * @param string $pcre
     * @return PCRECompiler
     */
    public function add(string $token, string $pcre): self
    {
        $this->tokens[$token] = $pcre;

        return $this;
    }

    /**
     * @return string
     */
    public function compile(): string
    {
        $tokens = \array_merge($this->tokens, [Unknown::T_NAME => '.*?']);

        return $this->tokensToPattern($tokens);
    }

    /**
     * @param iterable $tokens
     * @return string
     */
    private function tokensToPattern(iterable $tokens): string
    {
        $tokensList = [];

        foreach ($tokens as $name => $pcre) {
            $name = $this->escapeTokenName($name);
            $value = $this->escapeTokenPattern($pcre);

            $tokensList[] = \sprintf('(?P<%s>%s)', \trim($name), $value);
        }

        $pcre = \implode('|', $tokensList);

        return \sprintf('%s\G%s%1$s%s', self::REGEX_DELIMITER, $pcre, $this->renderFlags());
    }

    /**
     * @param string $pattern
     * @return string
     */
    protected function escapeTokenName(string $pattern): string
    {
        return \preg_quote($pattern, static::REGEX_DELIMITER);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function escapeTokenPattern(string $value): string
    {
        return \str_replace(static::REGEX_DELIMITER, '\\' . self::REGEX_DELIMITER, $value);
    }

    /**
     * @return string
     */
    private function renderFlags(): string
    {
        return self::FLAG_UNICODE . self::FLAG_DOT_ALL . self::FLAG_ANALYZED;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
