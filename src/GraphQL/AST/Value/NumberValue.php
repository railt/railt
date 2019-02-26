<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

use Railt\GraphQL\Frontend\Parser;
use Railt\Parser\Ast\LeafInterface;

/**
 * Class NumberValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class NumberValue extends Value
{
    /**
     * @var int
     */
    public $value;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof LeafInterface) {
            $this->value = $this->parse($value);

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return \is_int($this->value) ? 'int' : 'float';
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param LeafInterface $value
     * @return float|int
     */
    private function parse(LeafInterface $value)
    {
        switch (true) {
            case $this->isHex($value):
                return $this->parseHex($value->getValue(1));

            case $this->isBinary($value):
                return $this->parseBin($value->getValue(1));

            case $this->isExponential($value):
                return $this->parseExponential($value->getValue());

            case $this->isFloat($value):
                return $this->parseFloat($value->getValue());

            case $this->isInt($value):
                return $this->parseInt($value->getValue());
        }

        return (float)$value->getValue();
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isHex(LeafInterface $leaf): bool
    {
        return $leaf->getName() === Parser::T_HEX_NUMBER;
    }

    /**
     * @param string $value
     * @return int
     */
    private function parseHex(string $value): int
    {
        return \hexdec($value);
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isBinary(LeafInterface $leaf): bool
    {
        return $leaf->getName() === Parser::T_BIN_NUMBER;
    }

    /**
     * @param string $value
     * @return int
     */
    private function parseBin(string $value): int
    {
        return \bindec($value);
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isExponential(LeafInterface $leaf): bool
    {
        return \substr_count(\mb_strtolower($leaf->getValue()), 'e') !== 0;
    }

    /**
     * @param string $value
     * @return float
     */
    private function parseExponential(string $value): float
    {
        return (float)$value;
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isFloat(LeafInterface $leaf): bool
    {
        return \substr_count($leaf->getValue(), '.') !== 0;
    }

    /**
     * @param string $value
     * @return float
     */
    private function parseFloat(string $value): float
    {
        return (float)$value;
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isInt(LeafInterface $leaf): bool
    {
        return $leaf->getName() === Parser::T_NUMBER && \substr_count($leaf->getValue(), '.') === 0;
    }

    /**
     * @param string $value
     * @return int
     */
    private function parseInt(string $value): int
    {
        return $value >> 0;
    }
}
