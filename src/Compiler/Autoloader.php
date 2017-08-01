<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Illuminate\Support\Str;
use Serafim\Railgun\Compiler\Reflection\Definition\Definition;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Class Autoloader
 * @package Serafim\Railgun\Compiler
 */
class Autoloader
{
    private const DEFAULT_EXTENSIONS = [
        '.graphqls',
        '.graphqle',
        '.graphql',
        '.gql',
    ];

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
     * @return Autoloader|$this
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
     * @return null|NamedDefinitionInterface
     * @throws \Serafim\Railgun\Compiler\Exceptions\TypeException
     * @throws \Serafim\Railgun\Compiler\Exceptions\CompilerException
     * @throws Exceptions\NotReadableException
     * @throws Exceptions\SemanticException
     * @throws Exceptions\TypeNotFoundException
     * @throws Exceptions\UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     */
    public function load(string $type): ?NamedDefinitionInterface
    {
        foreach ($this->loaders as $loader) {
            $file = $loader($type);

            if (is_string($file)) {
                $document = $this->compiler->compileFile($file);

                return $this->compiler->getDictionary()->definition($document, $type);
            }
        }

        return null;
    }

    /**
     * @param string $directory
     * @param bool $prepend
     * @param array $extensions
     * @return Autoloader
     */
    public function psr0(
        string $directory,
        bool $prepend = false,
        array $extensions = self::DEFAULT_EXTENSIONS
    ): Autoloader
    {
        if (!Str::endsWith($directory, '/')) {
            $directory .= '/';
        }

        return $this->autoload(function (string $type) use ($directory, $extensions): ?string {
            foreach ($extensions as $extension) {
                $path = $directory . $type . $extension;
                if (is_file($path) && is_readable($path)) {
                    return $path;
                }
            }
            return null;
        }, $prepend);
    }
}
