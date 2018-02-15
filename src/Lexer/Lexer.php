<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer;

use Psr\Log\LoggerInterface;
use Railt\Io\Readable;
use Railt\Lexer\Stream\Stream;
use Railt\Lexer\Stream\TokenStream;

/**
 * Class Lexer
 */
class Lexer implements LexerInterface
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var iterable
     */
    private $tokens;

    /**
     * Lexer constructor.
     * @param iterable $tokens
     * @param Configuration|null $config
     * @param LoggerInterface|null $logger
     */
    public function __construct(iterable $tokens, Configuration $config = null, LoggerInterface $logger = null)
    {
        $this->tokens = $tokens;
        $this->config = $config ?? new Configuration();
    }

    /**
     * @return iterable
     */
    public function getTokens(): iterable
    {
        return $this->tokens;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->config;
    }

    /**
     * @param Readable $input
     * @return Stream
     */
    public function read(Readable $input): Stream
    {
        return new TokenStream(new Context($this->tokens, $this->config, $input));
    }
}
