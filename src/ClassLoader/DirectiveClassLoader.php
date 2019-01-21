<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\ClassLoader;

use Railt\SDL\Contracts\Definitions\Definition;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;

/**
 * Class DirectiveClassLoader
 */
class DirectiveClassLoader implements ClassLoaderInterface
{
    /**
     * @var CompilerInterface|Configuration
     */
    private $compiler;

    /**
     * @var array|string
     */
    private $global = [];

    /**
     * @var array|string[]
     */
    private $documents = [];

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * DirectiveClassLoader constructor.
     *
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param Document $document
     * @param string $needle
     * @return string
     */
    public function find(Document $document, string $needle): string
    {
        $this->bootIfNotBooted();
        $this->loadGlobalDirectives($document);

        $needleAlias = $this->unpackPrefix($needle);

        foreach ($this->getAllowedClasses($document) as $alias => $relation) {
            if ($alias === $needleAlias) {
                return $this->replacePrefix($alias, $relation, $needle);
            }
        }

        return $needle;
    }

    /**
     * @return void
     */
    private function bootIfNotBooted(): void
    {
        if ($this->booted === false) {
            /** @var Definition $definition */
            foreach ($this->compiler->getDictionary() as $definition) {
                $this->loadGlobalDirectives($definition->getDocument());
            }

            $this->booted = true;
        }
    }

    /**
     * @param Document $document
     */
    private function loadGlobalDirectives(Document $document): void
    {
        $hash = $document->getFile()->getHash();

        if (\in_array($hash, $this->documents, true)) {
            return;
        }

        $this->documents[] = $hash;

        foreach ($this->getUseDirectives($document) as $use) {
            if ($this->isGlobal($use)) {
                [$alias, $class] = $this->read($use);

                $this->global[$alias] = $class;
            }
        }
    }

    /**
     * @param Document $document
     * @return iterable|DirectiveInvocation[]
     */
    private function getUseDirectives(Document $document): iterable
    {
        return $document->getDirectives('use');
    }

    /**
     * @param DirectiveInvocation $directive
     * @return bool
     */
    private function isGlobal(DirectiveInvocation $directive): bool
    {
        return $directive->getPassedArgument('scope') === 'GLOBAL';
    }

    /**
     * @param DirectiveInvocation $directive
     * @return array
     */
    private function read(DirectiveInvocation $directive): array
    {
        [$class, $alias] = [
            $directive->getPassedArgument('class'),
            $directive->getPassedArgument('as'),
        ];

        return [$alias ?? $this->unpackSuffix($class), $class];
    }

    /**
     * @param string $class
     * @return string
     */
    private function unpackSuffix(string $class): string
    {
        $parts = \explode('\\', $class);

        return \end($parts);
    }

    /**
     * @param string $class
     * @return string
     */
    private function unpackPrefix(string $class): string
    {
        $parts = \explode('\\', $class);

        return \reset($parts);
    }

    /**
     * @param Document $document
     * @return iterable|string[]
     */
    private function getAllowedClasses(Document $document): iterable
    {
        foreach ($this->getUseDirectives($document) as $directive) {
            [$alias, $class] = $this->read($directive);

            yield $alias => $class;
        }

        yield from $this->global;
    }

    /**
     * @param string $alias
     * @param string $relation
     * @param string $needle
     * @return string
     */
    private function replacePrefix(string $alias, string $relation, string $needle): string
    {
        $needleChunks = \explode('\\', $needle);

        if (\reset($needleChunks) === $alias) {
            \array_shift($needleChunks);
        }

        return \implode('\\', \array_merge([$relation], $needleChunks));
    }
}
