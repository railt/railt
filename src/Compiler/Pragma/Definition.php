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
 * Interface Definition
 */
interface Definition
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public static function getDefaultValue();
}
