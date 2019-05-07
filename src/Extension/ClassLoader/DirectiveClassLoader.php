<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\ClassLoader;

use Railt\Container\ContainerInterface;
use Railt\Container\SignatureResolver;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Extension\ClassLoader\Exception\UnknownClassException;

/**
 * Class DirectiveClassLoader
 */
class DirectiveClassLoader implements ClassLoaderInterface
{
    /**
     * @var SignatureResolver
     */
    private $signature;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * DirectiveClassLoader constructor.
     *
     * @param CompilerInterface $compiler
     * @param ContainerInterface $container
     */
    public function __construct(CompilerInterface $compiler, ContainerInterface $container)
    {
        $this->repository = new Repository($compiler);
        $this->signature = new SignatureResolver($container);
    }

    /**
     * @param Document $document
     * @param string $needle
     * @return string
     * @throws \InvalidArgumentException
     * @throws UnknownClassException
     */
    public function find(Document $document, string $needle): string
    {
        $aliases = $this->repository->getAliases($document);
        $class = $this->signature->fetchClass($needle);

        if ($class === null) {
            $error = \sprintf('Route action class "%s" not found', $needle);
            throw new UnknownClassException($error);
        }

        $alias = $this->prefix($class);

        if (isset($aliases[$alias])) {
            $needle = \substr_replace($needle, $aliases[$alias], 0, \strlen($alias));
        }

        return $needle;
    }

    /**
     * @param string $class
     * @return string
     */
    private function prefix(string $class): string
    {
        $parts = \explode('\\', $class);

        return (string)\reset($parts);
    }
}
