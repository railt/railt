<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Value;

use Phplrt\Contracts\Lexer\Exception\LexerRuntimeExceptionInterface;

/**
 * Class StringValue
 */
final class StringValue extends Value
{
    /**
     * @var string
     */
    private const ERROR_INVALID_TYPE = 'GraphQL String cannot represent a non-string value of type %s';

    /**
     * @var string
     */
    private const ERROR_ENCODING = 'GraphQL String value contain invalid character sequence: %s';

    /**
     * @var string
     */
    public string $value;

    /**
     * StringValue constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function parse($value): ValueInterface
    {
        try {
            $value = (string)$value;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_TYPE, \gettype($value)));
        }

        try {
            return new static(Encoder::getInstance()->encode($value));
        } catch (LexerRuntimeExceptionInterface $e) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_ENCODING, $e->getMessage()));
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function toPHPValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return \json_encode($this->value, \JSON_THROW_ON_ERROR);
    }
}
