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
 * Class Root
 */
final class Root extends BasePragma
{
    /**
     * @return null|string
     */
    public static function getDefaultValue(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'root';
    }

    /**
     * @param string $value
     * @return string
     */
    public static function parse(string $value): string
    {
        return $value;
    }
}
