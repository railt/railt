<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Pragma;

/**
 * Class LexerVerification
 */
class LexerVerification extends BaseDefinition
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'lexer.verify';
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
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     */
    public function getValue(): bool
    {
        return $this->toBoolean();
    }
}
