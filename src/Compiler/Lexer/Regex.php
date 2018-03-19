<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

/**
 * Class Regex
 */
class Regex
{
    private const REGEX_DELIMITER = '/';

    private const F_MULTILINE = 'm';
    private const F_UNICODE  = 'u';
    private const F_DOT_ALL  = 's';

    /**
     * @var bool
     */
    private $modeIsUnicode = true;

    /**
     * @var bool
     */
    private $modeDotAll = false;

    /**
     * @var bool
     */
    private $modeMultiline = true;

    /**
     * @var array|Definition[]
     */
    private $tokens = [];

    /**
     * Regex constructor.
     * @param iterable|Definition[] $tokens
     */
    public function __construct(iterable $tokens = [])
    {
        foreach ($tokens as $token) {
            $this->tokens[] = $token;
        }
    }

    /**
     * @param Definition $token
     * @return Regex
     */
    public function addToken(Definition $token): Regex
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * @param Definition[] ...$tokens
     * @return Regex
     */
    public function addTokens(Definition ...$tokens): Regex
    {
        $this->tokens += $tokens;

        return $this;
    }

    /**
     * @return array|Definition[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return string
     */
    private function flags(): string
    {
        $flags = '';

        if ($this->modeMultiline) {
            $flags .= self::F_MULTILINE;
        }

        if ($this->modeIsUnicode) {
            $flags .= self::F_UNICODE;
        }

        if ($this->modeDotAll) {
            $flags .= self::F_DOT_ALL;
        }

        return $flags;
    }

    /**
     * @param bool|null $enabled
     * @return bool
     */
    public function dotAll(bool $enabled = null): bool
    {
        if ($enabled !== null) {
            $this->modeDotAll = $enabled;
        }

        return $this->modeDotAll;
    }

    /**
     * @param bool|null $enabled
     * @return bool
     */
    public function multiline(bool $enabled = null): bool
    {
        if ($enabled !== null) {
            $this->modeMultiline = $enabled;
        }

        return $this->modeMultiline;
    }

    /**
     * @param bool|null $enabled
     * @return bool
     */
    public function unicode(bool $enabled = null): bool
    {
        if ($enabled !== null) {
            $this->modeIsUnicode = $enabled;
        }

        return $this->modeIsUnicode;
    }

    /**
     * @param iterable|Definition[] $tokens
     * @return \Traversable
     */
    private function tokensToPattern(iterable $tokens): \Traversable
    {
        foreach ($tokens as $token) {
            $name  = $this->escapePattern($this->createTokenName($token));
            $value = $this->escapeString($token->getValue());

            yield \sprintf('(?<%s>%s)', \trim($name), $value);
        }
    }

    /**
     * @param string $value
     * @return string
     */
    private function escapeString(string $value): string
    {
        return \str_replace(self::REGEX_DELIMITER, '\\' . self::REGEX_DELIMITER, $value);
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
     * @param Definition $definition
     * @return string
     */
    private function createTokenName(Definition $definition): string
    {
        return 'T' . $definition->getId();
    }

    /**
     * @return string
     */
    protected function render(): string
    {
        $tokens = $this->tokensToPattern($this->tokens);

        return \implode('|', \iterator_to_array($tokens));
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $d = self::REGEX_DELIMITER;

        return \sprintf( '%s(%s)%1$s%s', $d, $this->render(), $this->flags());
    }

    /**
     * @param string $group
     * @return int
     */
    public static function id(string $group): int
    {
        return (int)\substr($group, 1);
    }
}
