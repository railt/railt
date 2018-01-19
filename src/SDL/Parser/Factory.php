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
use Railt\Compiler\Parser as BaseParser;
use Railt\Compiler\Runtime;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Parser
 */
class Factory
{
    /**
     * @var BaseParser
     */
    private $runtime;

    /**
     * @param BaseParser $parser
     * @return void
     */
    public function setRuntime(BaseParser $parser): void
    {
        $this->runtime = $parser;
    }

    /**
     * @param Readable $sources
     * @return NodeInterface
     */
    public function parse(Readable $sources): NodeInterface
    {
        return $this->getRuntime()->parse($sources->getContents());
    }

    /**
     * @return BaseParser
     */
    public function getRuntime(): BaseParser
    {
        if ($this->runtime === null) {
            $this->runtime = $this->resolveRuntime();
        }

        return $this->runtime;
    }

    /**
     * @return Readable
     */
    public static function getGrammarFile(): Readable
    {
        return File::fromPathname(__DIR__ . '/../resources/grammar/sdl.pp');
    }

    /**
     * @return BaseParser
     */
    private function resolveRuntime(): BaseParser
    {
        if (\class_exists(Compiled::class)) {
            return new Compiled();
        }

        return new Runtime(static::getGrammarFile());
    }

    /**
     * @return void
     */
    public function compile(): void
    {
        $generator = new Generator($this->getRuntime());
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
