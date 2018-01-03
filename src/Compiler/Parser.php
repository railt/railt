<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Kernel\CallStack;
use Railt\Parser\Io\Readable;
use Railt\Parser\Runtime;
use Railt\Parser\TreeNode;
use Railt\Parser\Visitor\Dump;
use Railt\Reflection\Filesystem\File;

/**
 * Class Parser
 */
class Parser
{
    /**
     * @var Runtime
     */
    private $runtime;

    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->runtime = new Runtime(File::fromPathname(__DIR__ . '/resources/grammar/sdl.pp'));
        $this->profiler = new Profiler($this->runtime);
    }

    /**
     * @param Readable $sources
     * @return TreeNode
     */
    public function parse(Readable $sources): TreeNode
    {
        return $this->runtime->parse($sources->getContents());
    }

    public function compile(): void
    {
        //
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        return $this->profiler->dump($ast);
    }
}
