<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Runtime;

use Illuminate\Support\Str;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Runtime\Contracts\ClassLoader;
use Railt\Runtime\Exceptions\UnknownClassException;

/**
 * Class DirectiveLoader
 */
class DirectiveLoader implements ClassLoader
{
    /**
     * @param Document $document
     * @param string $class
     * @return string
     * @throws \Railt\Runtime\Exceptions\UnknownClassException
     */
    public function load(Document $document, string $class): string
    {
        if (\class_exists($class)) {
            return $class;
        }

        $result = $this->loadFromDirective($document, $class);

        if ($result !== null) {
            return $result;
        }

        $error = 'Class "%s" is not found in the definition of route action argument';
        throw new UnknownClassException(\sprintf($error, $class));
    }

    /**
     * @param Document $document
     * @param string $needle
     * @return null|string
     */
    private function loadFromDirective(Document $document, string $needle): ?string
    {
        foreach ($document->getDirectives('use') as $directive) {
            [$class, $alias] = $this->getDirectiveArguments($directive);

            switch (true) {
                case $this->compareAlias($needle, $alias):
                    return $class;
                case $this->compareNamespace($class, $alias):
                    return $class;
            }
        }

        return null;
    }

    /**
     * @param string $class
     * @param string $alias
     * @return bool
     */
    private function compareAlias(string $class, ?string $alias): bool
    {
        return $alias === $class;
    }

    /**
     * @param string $class
     * @param string $alias
     * @return bool
     */
    private function compareNamespace(string $class, ?string $alias): bool
    {
        return Str::endsWith($class, '\\' . $alias) && \class_exists($class);
    }

    /**
     * @param DirectiveInvocation $directive
     * @return array
     */
    private function getDirectiveArguments(DirectiveInvocation $directive): array
    {
        return [
            $directive->getPassedArgument('class'),
            $directive->getPassedArgument('as')
        ];
    }
}
