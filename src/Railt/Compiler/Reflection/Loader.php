<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Kernel\CallStack;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Filesystem\File;
use Railt\Reflection\Filesystem\NotReadableException;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Class Loader
 */
class Loader extends Repository
{
    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * @var array|\Closure[]
     */
    private $loaders = [];

    /**
     * Loader constructor.
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler, CallStack $stack)
    {
        parent::__construct($stack);
        $this->compiler = $compiler;
    }

    /**
     * @param \Closure $resolver
     * @return $this
     */
    public function autoload(\Closure $resolver)
    {
        $this->loaders[] = $resolver;

        return $this;
    }

    /**
     * @param string $name
     * @return TypeDefinition
     * @throws NotReadableException
     * @throws TypeNotFoundException
     */
    public function get(string $name): TypeDefinition
    {
        try {
            return parent::get($name);
        } catch (TypeNotFoundException $error) {
            return $this->load($name);
        }
    }

    /**
     * @param string $name
     * @return TypeDefinition
     * @throws TypeNotFoundException
     * @throws NotReadableException
     */
    private function load(string $name): TypeDefinition
    {
        foreach ($this->loaders as $loader) {
            $result = $this->parseResult($loader($name));

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
     * @param string|ReadableInterface|mixed $result
     * @return null|ReadableInterface
     * @throws NotReadableException
     */
    private function parseResult($result): ?ReadableInterface
    {
        if (\is_string($result)) {
            return File::fromPathname($result);
        }

        if ($result instanceof ReadableInterface) {
            return $result;
        }

        return null;
    }

    /**
     * @param string $name
     * @param ReadableInterface $readable
     * @return null|TypeDefinition
     */
    private function findType(string $name, ReadableInterface $readable): ?TypeDefinition
    {
        $document = $this->compiler->compile($readable);

        return $document->getTypeDefinition($name);
    }
}
