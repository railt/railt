<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Exceptions;

use Railt\Compiler\Exceptions\CustomErrorPosition;

/**
 * Class LexerException
 */
class LexerException extends \LogicException
{
    use CustomErrorPosition;
}
