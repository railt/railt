<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Result;

use Railt\Compiler\TokenInterface;

/**
 * Class BaseToken
 */
abstract class BaseToken implements TokenInterface
{
    /**
     * @var int|null
     */
    private $length;

    /**
     * @var int|null
     */
    private $bytes;

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

        return \sprintf('"%s" (%s)', $format($this->value()), $this->name());
    }

    /**
     * @return int
     */
    public function bytes(): int
    {
        if ($this->bytes === null) {
            $this->bytes = \strlen($this->value());
        }

        return $this->bytes;
    }

    /**
     * @return int
     */
    public function length(): int
    {
        if ($this->length === null) {
            $this->length = \mb_strlen($this->value());
        }

        return $this->length;
    }
}
