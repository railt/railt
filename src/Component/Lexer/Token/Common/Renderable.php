<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Lexer\Token\Common;

/**
 * Trait Renderable
 */
trait Renderable
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        $format = function (string $value) {
            $value = (string)(\preg_replace('/\s+/iu', ' ', $value) ?? $value);
            $value = \addcslashes($value, '"');

            if (\mb_strlen($value) > 35) {
                $value = \mb_substr($value, 0, 30) .
                    \sprintf('… (%s+)', \mb_strlen($value) - 30);
            }

            return $value;
        };

        return \sprintf('"%s" (%s)', $format($this->getValue()), $this->getName());
    }
}
