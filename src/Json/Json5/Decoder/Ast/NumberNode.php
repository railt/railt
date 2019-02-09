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
abstract class NumberNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    protected $value;

    /**
     * @var int
     */
    private $options;

    /**
     * BoolNode constructor.
     *
     * @param array $children
     * @param int $options
     */
    public function __construct(array $children = [], int $options = 0)
    {
        $this->options = $options;
        $this->value = \reset($children);
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isStringable(string $value): bool
    {
        return (bool)($this->options & \JSON_BIGINT_AS_STRING);
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isFloatOrOverflow(string $value): bool
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
