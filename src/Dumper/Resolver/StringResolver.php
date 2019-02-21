<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper\Resolver;

/**
 * Class StringResolver
 */
class StringResolver extends Resolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return \is_string($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'string';
    }

    /**
     * @param mixed $string
     * @return string
     */
    public function value($string): string
    {
        $string = \addcslashes($string, '"');

        return $this->shorten($string, '"%s"', '"%s…" +%d');
    }

    /**
     * @param string $value
     * @param string $patternNormal
     * @param string $patternOverflow
     * @return string
     */
    private function shorten(string $value, string $patternNormal = '%s', string $patternOverflow = '%s… +%d'): string
    {
        $length = \mb_strlen($value);
        $overflows = $length > 15;

        if (! $overflows) {
            return \sprintf($patternNormal, $value);
        }

        $value = \mb_substr($value, 0, 10);

        return \sprintf($patternOverflow, $value, $length - 10);
    }
}
