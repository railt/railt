<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Iterator\RegexNamedGroupsIterator;
use Railt\Compiler\Lexer\Result\Eoi;
use Railt\Compiler\Lexer\Result\Token;
use Railt\Compiler\Lexer\Result\Unknown;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class NativeStateful
 */
class NativeStateful implements Stateful
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var array
     */
    protected $skipped;

    /**
     * NativeStateful constructor.
     * @param string $pattern
     * @param array $skipped
     */
    public function __construct(string $pattern, array $skipped = [])
    {
        $this->pattern = $pattern;
        $this->skipped = $skipped;
    }

    /**
     * @param Readable $input
     * @return \Traversable
     */
    public function lex(Readable $input): \Traversable
    {
        foreach ($this->exec($this->pattern, $input->getContents()) as $token) {
            if (! \in_array($token->name(), $this->skipped, true)) {
                yield $token;
            }
        }
    }

    /**
     * @param string $pattern
     * @param string $content
     * @return \Traversable|TokenInterface[]
     */
    protected function exec(string $pattern, string $content): \Traversable
    {
        $offset = 0;
        $regex  = new RegexNamedGroupsIterator($pattern, $content);

        $iterator = $regex->getIterator();

        while ($iterator->valid()) {
            /** @var TokenInterface $token */
            $token = $iterator->key() === Token::UNKNOWN_TOKEN
                ? $this->unknown($iterator, $offset)
                : $this->token($iterator, $offset);

            $offset += $token->bytes();

            yield $token;
        }

        yield new Eoi($offset);
    }

    /**
     * @param \Traversable $iterator
     * @param int $offset
     * @return Unknown
     */
    private function unknown(\Traversable $iterator, int $offset): TokenInterface
    {
        $body = $iterator->current()[0];
        $iterator->next();

        $body .= $this->collapse($iterator, TokenInterface::UNKNOWN_TOKEN);

        return new Unknown($body, $offset);
    }

    /**
     * @param \Traversable $iterator
     * @return string
     */
    private function collapse(\Traversable $iterator, string $token): string
    {
        $body = '';

        while ($iterator->valid()) {
            if ($iterator->key() !== $token) {
                break;
            }

            $body .= $iterator->current()[0];

            $iterator->next();
        }

        return $body;
    }

    /**
     * @param \Traversable $iterator
     * @param int $offset
     * @return Token
     */
    private function token(\Traversable $iterator, int $offset)
    {
        [$name, $context] = [$iterator->key(), $iterator->current()];

        $iterator->next();

        return new Token($name, $context, $offset);
    }
}
