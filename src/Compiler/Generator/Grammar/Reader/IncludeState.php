<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader;

use Psr\Log\LoggerInterface;
use Railt\Compiler\Generator\Grammar\Exceptions\InvalidInclusionException;
use Railt\Compiler\Lexer\Tokens\Output;
use Railt\Io\Exceptions\NotReadableException;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class IncludeState
 */
class IncludeState implements State
{
    /**
     * @var \SplStack
     */
    private $includes;

    /**
     * IncludeState constructor.
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->includes = new \SplStack();
    }

    /**
     * @param Readable $grammar
     * @param array $token
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    public function resolve(Readable $grammar, array $token): void
    {
        $name   = \reset($token[Output::I_TOKEN_CONTEXT]);
        $offset = $token[Output::I_TOKEN_OFFSET];

        /**
         * TODO Check and make sure that the path is unique and the file has not been previously connected
         */
        $file = $this->include($grammar, $name, $offset);

        $this->includes->push($file);
    }

    /**
     * @param Readable $grammar
     * @param string $path
     * @param int $offset
     * @return Readable
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    private function include(Readable $grammar, string $path, int $offset): Readable
    {
        $inclusion = \dirname($grammar->getPathname()) . \DIRECTORY_SEPARATOR . $path;

        try {
            return File::fromPathname($inclusion);
        } catch (NotReadableException $e) {
            throw InvalidInclusionException::fromFile($e->getMessage(), $grammar, $grammar->getPosition($offset));
        }
    }

    /**
     * @return iterable|\SplStack|Readable[]
     */
    public function getData(): iterable
    {
        return $this->includes;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->includes->count() === 0;
    }

    /**
     * @return Readable
     */
    public function pop(): Readable
    {
        return $this->includes->pop();
    }
}
