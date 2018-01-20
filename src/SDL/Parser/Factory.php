<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Debug\NodeDumper;
use Railt\Compiler\Generator;
use Railt\Compiler\Parser;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Parser
 */
class Factory
{
    public const GRAMMAR_FILE = __DIR__ . '/../resources/grammar/sdl.pp';

    /**
     * @var Parser|null
     */
    private $parser;

    /**
     * @return Parser
     */
    public function getParser(): Parser
    {
        if ($this->parser === null) {
            $this->parser = $this->createParser();
        }

        return $this->parser;
    }

    /**
     * @param Parser $parser
     * @return Factory
     */
    public function setParser(Parser $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return Parser
     */
    private function createParser(): Parser
    {
        if (\class_exists(Compiled::class)) {
            return new Compiled();
        }

        return Parser::fromGrammar(File::fromPathname(static::GRAMMAR_FILE));
    }

    /**
     * @param Readable $sources
     * @return NodeInterface
     */
    public function parse(Readable $sources): NodeInterface
    {
        return $this->getParser()->parse($sources->getContents());
    }

    /**
     * @return void
     */
    public function compile(): void
    {
        $generator = new Generator($this->getParser());
        $generator->setNamespace(__NAMESPACE__);
        $generator->saveTo('Compiled', __DIR__);
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
