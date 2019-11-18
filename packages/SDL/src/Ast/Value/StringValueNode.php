<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

use Phplrt\Contracts\Lexer\Exception\LexerExceptionInterface;
use Phplrt\Contracts\Lexer\Exception\LexerRuntimeExceptionInterface;

/**
 * Class StringValueNode
 *
 * <code>
 *  export type StringValueNode = {
 *      +kind: 'StringValue',
 *      +loc?: Location,
 *      +value: string,
 *      +block?: boolean,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L326
 */
class StringValueNode extends ValueNode
{
    /**
     * @var bool
     */
    public bool $block = false;

    /**
     * @var string
     */
    public string $value;

    /**
     * @var string|null
     */
    private ?string $encoded = null;

    /**
     * StringValueNode constructor.
     *
     * @param string $value
     * @param bool $block
     */
    public function __construct(string $value, bool $block = true)
    {
        $this->value = $value;
        $this->block = $block;
    }

    /**
     * @param string $value
     * @param bool $block
     * @return static
     */
    public static function parse(string $value, bool $block = false): self
    {
        return new static($value, $block);
    }

    /**
     * @return mixed|string|null
     * @throws LexerExceptionInterface
     * @throws LexerRuntimeExceptionInterface
     */
    public function toNative(): string
    {
        if ($this->encoded === null) {
            $this->encoded = Encoder::getInstance()->encode($this->value);
        }

        return $this->encoded;
    }
}
