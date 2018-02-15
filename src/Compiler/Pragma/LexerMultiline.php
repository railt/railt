<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Pragma;

/**
 * Class LexerMultiline
 */
class LexerMultiline extends BaseDefinition
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'lexer.multiline';
    }

    /**
     * @return bool
     */
    public static function getDefaultValue(): bool
    {
        return true;
    }

    /**
     * @return bool
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function getValue(): bool
    {
        return $this->toBoolean();
    }
}
