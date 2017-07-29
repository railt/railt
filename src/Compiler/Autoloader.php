<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Serafim\Railgun\Compiler\Reflection\Definition;

/**
 * Class Autoloader
 * @package Serafim\Railgun\Compiler
 */
class Autoloader
{
    /**
     * @var array|\Closure[]
     */
    private $loaders = [];

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * Autoloader constructor.
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param \Closure $then
     * @param bool $prepend
     * @return Autoloader
     */
    public function autoload(\Closure $then, bool $prepend = false): Autoloader
    {
        if ($prepend) {
            array_unshift($this->loaders, $then);
        } else {
            $this->loaders[] = $then;
        }

        return $this;
    }

    /**
     * @param string $type
     * @return null|Definition
     * @throws Exceptions\NotReadableException
     * @throws Exceptions\SemanticException
     * @throws Exceptions\TypeNotFoundException
     * @throws Exceptions\UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     */
    public function load(string $type): ?Definition
    {
        foreach ($this->loaders as $loader) {
            $file = $loader($type);

            if (is_string($file)) {
                $this->compiler->parseFile($file);

                if ($this->compiler->getDictionary()->has($type)) {
                    return $this->compiler->getDictionary()->get($type);
                }
            }
        }

        return null;
    }
}
