<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Compiler;

use Illuminate\Support\Str;
use Railgun\Compiler\Autoloader\Directory;
use Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Railgun\Support\File;

/**
 * Class Autoloader
 * @package Railgun\Compiler
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
     * @param string $type
     * @return null|NamedDefinitionInterface
     * @throws \Railgun\Exceptions\NotReadableException
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     */
    public function load(string $type): ?NamedDefinitionInterface
    {
        foreach ($this->loaders as $loader) {
            $file = $loader($type);

            if (is_string($file)) {
                $out = $this->compiler->compile(File::path($file));

                return $this->compiler->getDictionary()->definition($out, $type);
            }
        }

        return null;
    }

    /**
     * @param string|array|string[] $directories
     * @param bool $prepend
     * @return Autoloader
     */
    public function dir($directories, bool $prepend = false): Autoloader
    {
        return $this->autoload(new Directory(...(array)$directories), $prepend);
    }

    /**
     * @param callable $then
     * @param bool $prepend
     * @return Autoloader|$this
     */
    public function autoload(callable $then, bool $prepend = false): Autoloader
    {
        if ($prepend) {
            array_unshift($this->loaders, $then);
        } else {
            $this->loaders[] = $then;
        }

        return $this;
    }
}
