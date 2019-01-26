<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support;

/**
 * Class Renderer
 */
final class Renderer
{
    /**
     * @var string
     */
    private const PATTERN_INT = 'int(%d)';

    /**
     * @var string
     */
    private const PATTERN_FLOAT = 'float(%F)';

    /**
     * @var string
     */
    private const PATTERN_BOOL = 'bool(%s)';

    /**
     * @var string
     */
    private const PATTERN_BOOL_TRUE = 'true';

    /**
     * @var string
     */
    private const PATTERN_BOOL_FALSE = 'false';

    /**
     * @var string
     */
    private const PATTERN_STRING = 'string("%s")';

    /**
     * @var string
     */
    private const PATTERN_STRING_ESCAPE_CHAR = '"';

    /**
     * @var string
     */
    private const PATTERN_NULL = 'null';

    /**
     * @var string
     */
    private const PATTERN_NON_RENDER_ITERABLE = '[ ... ]';

    /**
     * @var string
     */
    private const PATTERN_OBJECT = 'object(%s)#%d';

    /**
     * @var string
     */
    private const PATTERN_RESOURCE = 'resource(#%d)';

    /**
     * @var string
     */
    private const PATTERN_FN = 'fn(%s)#%d => { ... }';

    /**
     * @var string
     */
    private const PATTERN_FN_FALLBACK = 'fn(...)#0 => { ... }';

    /**
     * @var string
     */
    private const PATTERN_IT = 'iterable(%s<%s>)#%d { %s }';

    /**
     * @var string
     */
    private const PATTERN_IT_BODY = '[%s] => %s';

    /**
     * @var string
     */
    private const PATTERN_ARRAY = 'array(%d) { %s }';

    /**
     * @var string
     */
    private const PATTERN_ARRAY_BODY = '[%s] => %s';

    /**
     * @var string
     */
    private const PARAM_NULLABLE = '?';

    /**
     * @var string
     */
    private const PARAM_TYPE_ANY = 'mixed';

    /**
     * @var string
     */
    private const PARAM_NAME = ' $%s';

    /**
     * @var string
     */
    private const PARAM_DEFAULT_VALUE = ' = %s';

    /**
     * Renderer constructor.
     */
    private function __construct()
    {
        // This is static class. Constructor invocation is not allowed.
    }

    /**
     * @param mixed $argument
     * @return string
     */
    public static function renderType($argument): string
    {
        switch (true) {
            case \is_int($argument):
                return 'int';

            case \is_float($argument):
                return 'float';

            case \is_array($argument):
                return 'array';

            case \is_bool($argument):
                return 'bool';

            case \is_resource($argument):
                return 'resource';

            case $argument === null:
                return 'null';

            case \is_object($argument):
                return 'object';
        }

        return \strtolower(\gettype($argument));
    }

    /**
     * @param array $argument
     * @return string
     */
    public static function renderArray(array $argument): string
    {
        $result = [];

        foreach ($argument as $key => $value) {
            $result[] = \vsprintf(self::PATTERN_ARRAY_BODY, [
                self::render($key, false),
                self::render($value),
            ]);
        }

        return \sprintf(self::PATTERN_ARRAY, \count($argument), \implode(', ', $result));
    }

    /**
     * @param mixed $argument
     * @param bool $deep
     * @return string
     */
    public static function render($argument, bool $deep = true): string
    {
        switch (true) {
            case $argument === null:
                return self::PATTERN_NULL;

            case \is_scalar($argument):
                return self::renderScalar($argument);

            case $argument instanceof \Closure:
                return self::renderClosure($argument);

            case \is_array($argument):
                return $deep ? self::renderArray($argument) : self::PATTERN_NON_RENDER_ITERABLE;

            case \is_iterable($argument):
                return $deep ? self::renderIterator($argument) : self::PATTERN_NON_RENDER_ITERABLE;

            case \is_object($argument):
                return self::renderObject($argument);

            case \is_resource($argument):
                return self::renderResource($argument);
        }

        return '?';
    }

    /**
     * @param float|int|string|bool $argument
     * @return string
     */
    public static function renderScalar($argument): string
    {
        switch (true) {
            case \is_int($argument):
                return \sprintf(self::PATTERN_INT, $argument);

            case \is_float($argument):
                return \sprintf(self::PATTERN_FLOAT, $argument);

            case \is_bool($argument):
                $value = $argument ? self::PATTERN_BOOL_TRUE : self::PATTERN_BOOL_FALSE;

                return \sprintf(self::PATTERN_BOOL, $value);

            case \is_string($argument):
                $value = \addcslashes($argument, self::PATTERN_STRING_ESCAPE_CHAR);

                return \sprintf(self::PATTERN_STRING, $value);
        }

        return \print_r($argument, true);
    }

    /**
     * @param \Closure $argument
     * @return string
     */
    public static function renderClosure(\Closure $argument): string
    {
        try {
            $reflection = new \ReflectionFunction($argument);
            $parameters = [];

            foreach ($reflection->getParameters() as $parameter) {
                $parameters[] = self::renderParameter($parameter);
            }

            return \vsprintf(self::PATTERN_FN, [
                \implode(', ', $parameters),
                \spl_object_id($argument)
            ]);
        } catch (\ReflectionException $e) {
            return self::PATTERN_FN_FALLBACK;
        }
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     * @throws \ReflectionException
     */
    public static function renderParameter(\ReflectionParameter $parameter): string
    {
        // Is nullable?
        $result = $parameter->allowsNull() ? self::PARAM_NULLABLE : '';

        // Type hint
        $result .= ($type = $parameter->getType()) ? $type->getName() : self::PARAM_TYPE_ANY;

        // Name
        $result .= \sprintf(self::PARAM_NAME, $parameter->getName());

        // Default value
        if ($parameter->isDefaultValueAvailable()) {
            $result .= \sprintf(self::PARAM_DEFAULT_VALUE, self::render($parameter->getDefaultValue()));
        }

        return $result;
    }

    /**
     * @param \Traversable $argument
     * @return string
     */
    public static function renderIterator(\Traversable $argument): string
    {
        $result = [];
        $types = [];

        foreach ($argument as $key => $value) {
            $types[] = self::renderType($value);

            $result[] = \vsprintf(self::PATTERN_IT_BODY, [
                self::render($key, false),
                self::render($value),
            ]);
        }

        return \vsprintf(self::PATTERN_IT, [
            \get_class($argument),
            \implode('|', \array_unique($types)),
            \spl_object_id($argument),
            \implode(', ', $result),
        ]);
    }

    /**
     * @param object $argument
     * @return string
     */
    public static function renderObject($argument): string
    {
        if (\method_exists($argument, '__toString')) {
            return \sprintf(self::PATTERN_OBJECT, $argument, \spl_object_id($argument));
        }

        return \sprintf(self::PATTERN_OBJECT, \get_class($argument), \spl_object_id($argument));
    }

    /**
     * @param resource $argument
     * @return string
     */
    public static function renderResource($argument): string
    {
        return \sprintf(self::PATTERN_RESOURCE, $argument);
    }

    /**
     * @param mixed ...$arguments
     * @return string
     */
    public static function renderAll(...$arguments): string
    {
        $result = [];

        foreach ($arguments as $argument) {
            $result[] = self::render($argument);
        }

        return \implode(', ', $result);
    }
}
