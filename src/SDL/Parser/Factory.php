<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser;

use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Debug\NodeDumper;
use Railt\Compiler\ParserInterface;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Parser
 */
class Factory
{
    public const GRAMMAR_FILE = __DIR__ . '/../resources/grammar/sdl.pp';

    /**
     * @var ParserInterface|null
     */
    private $parser;

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface
    {
        if ($this->parser === null) {
            $this->parser = $this->createParser();
        }

        return $this->parser;
    }

    /**
     * @param ParserInterface $parser
     * @return Factory
     */
    public function setParser(ParserInterface $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return ParserInterface
     */
    private function createParser(): ParserInterface
    {
        if (\class_exists(SchemaParser::class)) {
            return new SchemaParser();
        }

        return (new Reader())->read(File::fromPathname(static::GRAMMAR_FILE))->getParser();
    }

    /**
     * @param Readable $sources
     * @return NodeInterface
     */
    public function parse(Readable $sources): NodeInterface
    {
        return $this->getParser()->parse($sources);
    }

    /**
     * @param NodeInterface $ast
     * @return string
     */
    public function dump(NodeInterface $ast): string
    {
        return (new NodeDumper($ast))->toString();
    }
}
