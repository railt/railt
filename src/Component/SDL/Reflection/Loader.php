<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection;

use Railt\Component\Io\File;
use Railt\Component\Io\Readable;
use Railt\Component\SDL\Contracts\Definitions\Definition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Exceptions\TypeNotFoundException;
use Railt\Component\SDL\Runtime\CallStackInterface;
use Railt\Component\SDL\Schema\CompilerInterface;

/**
 * Class Loader
 */
class Loader extends Repository
{
    /**
     * @var \Railt\Component\SDL\Schema\CompilerInterface
     */
    private $compiler;

    /**
     * @var array|\Closure[]
     */
    private $loaders = [];

    /**
     * Loader constructor.
     *
     * @param \Railt\Component\SDL\Schema\CompilerInterface $compiler
     * @param \Railt\Component\SDL\Runtime\CallStackInterface $stack
     */
    public function __construct(CompilerInterface $compiler, CallStackInterface $stack)
    {
        $this->compiler = $compiler;

        parent::__construct($stack);
    }

    /**
     * @param \Closure $resolver
     * @return $this
     */
    public function autoload(\Closure $resolver): self
    {
        $this->loaders[] = $resolver;

        return $this;
    }

    /**
     * @param string $name
     * @param \Railt\Component\SDL\Contracts\Definitions\Definition|null $from
     * @return \Railt\Component\SDL\Contracts\Definitions\TypeDefinition
     * @throws \Railt\Component\Io\Exception\NotReadableException
     * @throws \Railt\Component\SDL\Exceptions\TypeNotFoundException
     */
    public function get(string $name, Definition $from = null): TypeDefinition
    {
        if (parent::has($name)) {
            return parent::get($name, $from);
        }

        return $this->load($name, $from);
    }

    /**
     * @param string $name
     * @param \Railt\Component\SDL\Contracts\Definitions\Definition|null $from
     * @return \Railt\Component\SDL\Contracts\Definitions\TypeDefinition
     * @throws \Railt\Component\Io\Exception\NotReadableException
     * @throws \Railt\Component\SDL\Exceptions\TypeNotFoundException
     */
    private function load(string $name, Definition $from = null): TypeDefinition
    {
        foreach ($this->loaders as $loader) {
            $result = $this->parseResult($loader($name, $from));

            /**
             * Checks that the autoloader returns a valid file type.
             */
            if ($result !== null) {
                $type = $this->findType($name, $result);

                /**
                 * We check that this file contains the type definition
                 * we need, otherwise we ignore it.
                 */
                if ($type instanceof TypeDefinition) {
                    return $type;
                }
            }
        }


        $error = \sprintf('Type "%s" not found and could not be loaded', $name);
        throw new TypeNotFoundException($error, $this->stack);
    }

    /**
     * @param mixed $result
     * @return \Railt\Component\Io\Readable|null
     * @throws \Railt\Component\Io\Exception\NotReadableException
     */
    private function parseResult($result): ?Readable
    {
        if (\is_string($result)) {
            return File::fromPathname($result);
        }

        if ($result instanceof Readable) {
            return $result;
        }

        return null;
    }

    /**
     * @param string $name
     * @param \Railt\Component\Io\Readable $readable
     * @return \Railt\Component\SDL\Contracts\Definitions\TypeDefinition|null
     */
    private function findType(string $name, Readable $readable): ?TypeDefinition
    {
        $document = $this->compiler->compile($readable);

        return $document->getTypeDefinition($name);
    }
}
