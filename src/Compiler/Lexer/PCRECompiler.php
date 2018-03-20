<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Lexer\Result\Eof;

/**
 * Class PCRECompiler
 */
class PCRECompiler
{
    private const REGEX_DELIMITER = '/';

    private const F_MULTILINE = 'm';
    private const F_UNICODE   = 'u';
    private const F_DOT_ALL   = 's';

    /**
     * @var bool
     */
    private $modeGroupNative = true;

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
    private $definitions = [];

    /**
     * Regex constructor.
     * @param iterable|array|\Traversable|Definition[] $tokens
     */
    public function __construct(iterable $tokens = [])
    {
        foreach ($tokens as $def) {
            $this->addToken($def);
        }
    }

    /**
     * @param Definition $token
     * @return PCRECompiler
     */
    public function addToken(Definition $token): self
    {
        $this->definitions[$token->getName()] = $token;

        return $this;
    }

    /**
     * @return array|Definition[]
     */
    public function getTokens(): array
    {
        return $this->definitions;
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
     * @return string
     */
    public function compile(): string
    {
        $d = self::REGEX_DELIMITER;

        return \sprintf('%s%s%1$s%s', $d, $this->render(), $this->flags());
    }

    /**
     * @return string
     */
    protected function render(): string
    {
        $tokens = $this->tokensToPattern($this->definitions);

        return \implode('|', \iterator_to_array($tokens));
    }

    /**
     * @param iterable|Definition[] $definitions
     * @return \Traversable
     */
    private function tokensToPattern(iterable $definitions): \Traversable
    {
        foreach ($definitions as $def) {
            if ($def->getName() === Eof::NAME) {
                continue; // Do not compile EOF
            }

            $name  = $this->escapePattern($def->getName());
            $value = $this->escapeString($def->getValue());

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
}
