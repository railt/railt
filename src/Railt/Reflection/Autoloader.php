<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Autoloader\Directory;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\UnrecognizedNodeException;
use Railt\Support\Exceptions\NotReadableException;
use Railt\Support\Filesystem\File;

/**
 * Class Autoloader
 * @package Railt\Reflection
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
     * @throws TypeConflictException
     * @throws UnrecognizedNodeException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     * @throws NotReadableException
     */
    public function load(string $type): ?NamedDefinitionInterface
    {
        foreach ($this->loaders as $loader) {
            $file = $loader($type);

            if (is_string($file)) {
                $out = $this->compiler->compile(File::fromPathname($file));

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
