<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper;

/**
 * Interface FacadeInterface
 */
interface FacadeInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public static function render($value): string;

    /**
     * @param mixed $value
     * @return string
     */
    public static function renderValue($value): string;

    /**
     * @param mixed $value
     * @return string
     */
    public static function renderType($value): string;

    /**
     * @return array
     */
    public static function trace(): array;
}
