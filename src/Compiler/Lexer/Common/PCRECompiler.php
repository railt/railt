<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Common;

use Railt\Compiler\Exception\BadLexemeException;
use Railt\Compiler\TokenInterface;

/**
 * Class PCRECompiler
 */
class PCRECompiler
{
    private const REGEX_DELIMITER = '/';

    private const FLAG_UNICODE  = 'u';
    private const FLAG_DOT_ALL  = 's';
    private const FLAG_ANALYZED = 'S';

    /**
     * @var array|string[]
     */
    private $tokens = [];

    /**
     * @var bool
     */
    private $modeGroupNative = true;

    /**
     * Regex constructor.
     * @param iterable|string[] $tokens
     */
    public function __construct(iterable $tokens = [])
    {
        foreach ($tokens as $name => $pcre) {
            $this->addToken($name, $pcre);
        }
    }

    /**
     * @param string $name
     * @param string $pcre
     * @return PCRECompiler
     */
    public function addToken(string $name, string $pcre): self
    {
        $this->tokens[$name] = $pcre;

        return $this;
    }

    /**
     * @param string $pcre
     * @return bool
     * @throws BadLexemeException
     */
    public function test(string $pcre): bool
    {
        $result = @\preg_match($this->toPattern($pcre), '');

        if ($result === null || $result === false) {
            $message = \error_get_last()['message'] ?? \sprintf('Unprocessable PCRE %s', $pcre);
            $message = \str_replace('preg_match(): Compilation failed: ', '', $message);

            throw new BadLexemeException('Unprocessable PCRE (' . $message . ')');
        }

        return true;
    }

    /**
     * @param string $pcre
     * @return string
     */
    private function toPattern(string $pcre): string
    {
        return \sprintf('%s\G%s%1$s%s', self::REGEX_DELIMITER, $pcre, $this->flags());
    }

    /**
     * @return string
     */
    private function flags(): string
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

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->tokens);
    }

    /**
     * @param bool|null $basic
     * @return bool
     */
    public function capturing(bool $basic = null): bool
    {
        if ($basic !== null) {
            $this->modeGroupNative = $basic;
        }

        return $this->modeGroupNative;
    }

    /**
     * @return string
     */
    public function compile(): string
    {
        return $this->toPattern($this->render());
    }

    /**
     * @return string
     */
    protected function render(): string
    {
        $tokens = \array_merge($this->tokens, [TokenInterface::UNKNOWN_TOKEN => '.*?']);
        $tokens = \iterator_to_array($this->tokensToPattern($tokens));

        return \implode('|', $tokens);
    }

    /**
     * @param iterable|string[] $tokens
     * @return \Traversable
     */
    private function tokensToPattern(iterable $tokens): \Traversable
    {
        foreach ($tokens as $name => $pcre) {
            $name  = $this->escapePattern($name);
            $value = $this->escapeString($pcre);

            yield \vsprintf('(?%s<%s>%s)', [
                $this->modeGroupNative ? 'P' : '',
                \trim($name),
                $value,
            ]);
        }
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function escapePattern(string $pattern): string
    {
        return \preg_quote($pattern, self::REGEX_DELIMITER);
    }

    /**
     * @param string $value
     * @return string
     */
    private function escapeString(string $value): string
    {
        return \str_replace(self::REGEX_DELIMITER, '\\' . self::REGEX_DELIMITER, $value);
    }
}
