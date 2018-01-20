<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Pragmas;

/**
 * Interface Pragma
 */
interface Pragma
{
    /**
     * @return mixed
     */
    public static function getDefaultValue();

    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @param string $value
     * @return mixed
     */
    public static function parse(string $value);
}
