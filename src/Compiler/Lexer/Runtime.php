<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\LexerInterface;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class Runtime
 */
abstract class Runtime implements LexerInterface
{
    /**
     * @return string
     */
    abstract public function pattern(): string;

    /**
     * @return array
     */
    abstract public function channels(): array;

    /**
     * @return array
     */
    abstract public function identifiers(): array;

    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    public function lex(Readable $input): \Traversable
    {
        return new Stream($input, $this);
    }

    /**
     * @param int $id
     * @return string
     */
    public function channel(int $id): string
    {
        return $this->channels()[$id] ?? Token::CHANNEL_DEFAULT;
    }

    /**
     * @param int $id
     * @return int|mixed|string
     */
    public function name(int $id)
    {
        return $this->identifiers()[$id] ?? $id;
    }

    /**
     * @param int $token
     * @return bool
     */
    public function has($token): bool
    {
        return \in_array($token, $this->identifiers(), true);
    }
}
