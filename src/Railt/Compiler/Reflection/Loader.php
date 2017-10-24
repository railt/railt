<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Filesystem\File;
use Railt\Compiler\Filesystem\ReadableInterface;

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
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param \Closure $resolver
     * @return $this
     */
    public function registerAutoloader(\Closure $resolver)
    {
        $this->loaders[] = $resolver;

        return $this;
    }

    /**
     * @param string $name
     * @param Document|null $document
     * @return Definition
     * @throws TypeNotFoundException
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     */
    public function get(string $name, Document $document = null): Definition
    {
        try {
            return parent::get($name, $document);
        } catch (TypeNotFoundException $error) {
            return $this->load($name);
        }
    }

    /**
     * @param string $name
     * @return Definition
     * @throws TypeNotFoundException
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     */
    private function load(string $name): Definition
    {
        foreach ($this->loaders as $loader) {
            $file = $this->parseResult($loader($name));

            // The File exists
            if ($file) {
                $type = $this->findType($name, $file);

                // Target File contains required type
                if ($type !== null) {
                    return $type;
                }
            }
        }

        $error = 'GraphQL type "%s" not found and can not be loaded';
        throw new TypeNotFoundException(\sprintf($error, $name));
    }

    /**
     * @param string $name
     * @param ReadableInterface $readable
     * @return null|Definition
     */
    private function findType(string $name, ReadableInterface $readable): ?Definition
    {
        $document = $this->compiler->compile($readable);

        return $document->getDefinition($name);
    }

    /**
     * @param string|ReadableInterface|mixed $result
     * @return null|ReadableInterface
     * @throws \Railt\Compiler\Exceptions\NotReadableException
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
}
