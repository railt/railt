<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder\Ast;

use Railt\Parser\Ast\LeafInterface;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class NumberNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    private $value;

    /**
     * @var bool
     */
    private $float;

    /**
     * BoolNode constructor.
     *
     * @param string $name
     * @param array $children
     */
    public function __construct(string $name, array $children = [])
    {
        $this->value = \reset($children);
        $this->float = $this->value->is('T_FLOAT');
    }

    /**
     * @return float|int|mixed
     */
    public function reduce()
    {
        $value = $this->value->getValue();

        if ($this->float || $this->isFloatOrOverflow($value)) {
            $result = (float)$value;

            /** @noinspection TypeUnsafeComparisonInspection */
            if ($result != (int)$value) {
                return $result;
            }
        }

        return (int)$value;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isFloatOrOverflow(string $value): bool
    {
        if (\strpos($value, 'e') !== false) {
            return true;
        }

        switch (true) {
            case $this->isBCMathSupports():
                return $this->isBCMathOverflow($value);

            case $this->isGMPSupports():
                return $this->isGMPOverflow($value);

            default:
                // Fallback
                return \strlen($value) > (\strlen(\PHP_INT_MAX) - 1);
        }
    }

    /**
     * @return bool
     */
    private function isBCMathSupports(): bool
    {
        return \function_exists('\\bccomp');
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isBCMathOverflow(string $value): bool
    {
        return \bccomp($value, (string)\PHP_INT_MAX) > 0
            || \bccomp($value, (string)\PHP_INT_MIN) < 0;
    }

    /**
     * @return bool
     */
    private function isGMPSupports(): bool
    {
        return ! $this->float &&
            \function_exists('\\gmp_cmp') &&
            \function_exists('\\gmp_init');
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isGMPOverflow(string $value): bool
    {
        return \gmp_cmp(\gmp_init($value), \gmp_init((string)\PHP_INT_MAX)) > 0
            || \gmp_cmp(\gmp_init($value), \gmp_init((string)\PHP_INT_MIN)) < 0;
    }
}
