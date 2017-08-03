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
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Class Autoloader
 * @package Serafim\Railgun\Compiler
 */
class Autoloader
{
    private const EXTENSIONS = [
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
                $out = $this->compiler->compileFile($file);

                return $this->compiler->getDictionary()->definition($out, $type);
            }
        }

        return null;
    }

    /**
     * @param string|array|string[] $directories
     * @param bool $prepend
     * @param array $extensions
     * @return Autoloader
     */
    public function dir($directories, bool $prepend = false, array $extensions = self::EXTENSIONS): Autoloader
    {
        return $this->autoload(function (string $type) use ($directories, $extensions): ?string {
            foreach ((array)$directories as $dir) {
                if (!Str::endsWith($dir, '/')) {
                    $dir .= '/';
                }

                foreach ($extensions as $extension) {
                    $path = $dir . $type . $extension;
                    if (is_file($path) && is_readable($path)) {
                        return $path;
                    }
                }
            }

            return null;
        }, $prepend);
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
}
