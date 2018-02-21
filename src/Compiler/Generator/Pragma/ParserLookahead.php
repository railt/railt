<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Pragma;

use Railt\Compiler\Runtime\Parser;

/**
 * Class ParserLookahead
 */
class ParserLookahead extends BaseDefinition
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return Parser::PRAGMA_LOOKAHEAD;
    }

    /**
     * @return int
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     */
    public function getValue(): int
    {
        return $this->toInt();
    }

    /**
     * @return int
     */
    public static function getDefaultValue(): int
    {
        return 1024;
    }
}
