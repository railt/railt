<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\GraphQL\Compiler;
use Railt\GraphQL\ProgramInterface;
use Railt\Io\File;

if (! \function_exists('\\gql')) {
    /**
     * @param string $sources
     * @return ProgramInterface
     * @throws \Railt\GraphQL\Exception\InternalErrorException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    function gql(string $sources): ProgramInterface
    {
        return Compiler::getInstance()->compile(File::new($sources));
    }
}


if (! \function_exists('\\trait_uses_recursive')) {
    /**
     * @param string $trait
     * @param bool $autoload
     * @return array
     */
    function trait_uses_recursive(string $trait, bool $autoload = true): array
    {
        \assert(\trait_exists($trait));

        $traits = \class_uses($trait, $autoload);

        foreach ($traits as $child) {
            $traits += \trait_uses_recursive($child, $autoload);
        }

        return $traits;
    }
}


if (! \function_exists('\\class_uses_recursive')) {
    /**
     * @param string $class
     * @param bool $autoload
     * @return array
     */
    function class_uses_recursive(string $class, bool $autoload = true): array
    {
        \assert(\class_exists($class));

        $results = [];

        foreach (\array_reverse(\class_parents($class)) + [$class => $class] as $child) {
            $results += \trait_uses_recursive($child, $autoload);
        }

        return \array_unique($results);
    }
}

if (! \function_exists('\\class_implements_recursive')) {
    /**
     * @param string $class
     * @param bool $autoload
     * @return array
     */
    function class_implements_recursive(string $class, bool $autoload = true): array
    {
        \assert(\class_exists($class));

        $results = [];

        foreach (\array_reverse(\class_parents($class)) + [$class => $class] as $child) {
            $results += \class_implements($child, $autoload);
        }

        return \array_unique($results);
    }
}


if (! \function_exists('\\trait_constructors')) {
    /**
     * @param string $class
     * @param string $prefix
     * @param bool $autoload
     * @return array
     */
    function trait_constructors(string $class, string $prefix = '__construct', bool $autoload = true): array
    {
        \assert(\class_exists($class));

        $result = [];

        foreach (\class_uses_recursive($class, $autoload) as $trait) {
            $method = $prefix . \basename(\str_replace('\\', '/', $trait));

            if (\method_exists($class, $method)) {
                $result[] = $method;
            }
        }

        return $result;
    }
}


if (! \function_exists('\\class_basename')) {
    /**
     * @param string|object $classOrObject
     * @return string
     */
    function class_basename($classOrObject): string
    {
        \assert(\is_string($classOrObject) || \is_object($classOrObject));

        $class = \is_object($classOrObject) ? \get_class($classOrObject) : $classOrObject;

        \assert(\class_exists($class));

        return \basename(\str_replace('\\', '/', $class));
    }
}
