<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Parser;

use Railt\Compiler\Generator;
use Railt\Compiler\Io\Readable;
use Railt\Compiler\Parser as BaseParser;
use Railt\Compiler\Runtime;
use Railt\Compiler\TreeNode;
use Railt\Compiler\Visitor\Dump;
use Railt\Reflection\Filesystem\File;

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
     * @return TreeNode
     */
    public function parse(Readable $sources): TreeNode
    {
        return $this->getRuntime()->parse($sources->getContents());
    }

    /**
     * @return BaseParser
     */
    private function getRuntime(): BaseParser
    {
        if ($this->runtime === null) {
            $this->runtime = $this->resolveRuntime();
        }

        return $this->runtime;
    }

    /**
     * @return BaseParser
     */
    private function resolveRuntime(): BaseParser
    {
        if (\class_exists(Compiled::class)) {
            return new Compiled();
        }

        $grammar = File::fromPathname(__DIR__ . '/../resources/grammar/sdl.pp');

        return new Runtime($grammar);
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
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        return (new Dump())->dump($ast);
    }
}
