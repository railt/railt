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
 * Class ParserRootRule
 */
class ParserRootRule extends BaseDefinition
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'parser.entry';
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->toString();
    }

    /**
     * @return mixed|null
     */
    public static function getDefaultValue()
    {
        return null;
    }
}
