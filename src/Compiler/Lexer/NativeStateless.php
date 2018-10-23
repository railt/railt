<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Lexer\Common\PCRECompiler;
use Railt\Io\Readable;

/**
 * Class NativeStateless
 */
class NativeStateless extends NativeStateful implements Stateless
{
    /**
     * @var PCRECompiler
     */
    private $pcre;

    /**
     * NativeStateless constructor.
     */
    public function __construct()
    {
        $this->pcre = new PCRECompiler();
        parent::__construct('');
    }

    /**
     * @param Readable $input
     * @return \Traversable
     */
    public function lex(Readable $input): \Traversable
    {
        foreach ($this->exec($this->pcre->compile(), $input->getContents()) as $token) {
            if (! \in_array($token->name(), $this->skipped, true)) {
                yield $token;
            }
        }
    }

    /**
     * @param string $name
     * @return Stateless
     */
    public function skip(string $name): Stateless
    {
        $this->skipped[] = $name;

        return $this;
    }

    /**
     * @param string $name
     * @param string $pcre
     * @return Stateless
     */
    public function add(string $name, string $pcre): Stateless
    {
        $this->pcre->addToken($name, $pcre);

        return $this;
    }

    /**
     * @return iterable|string[]
     */
    public function getTokens(): iterable
    {
        return $this->pcre->getTokens();
    }

    /**
     * @return iterable|string[]
     */
    public function getIgnoredTokens(): iterable
    {
        return $this->skipped;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->pcre->has($name);
    }
}
