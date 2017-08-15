<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Routing;

use Railgun\Exceptions\CompilerException;
use Railgun\Exceptions\IndeterminateBehaviorException;

/**
 * Class Route
 * @package Railgun\Routing
 */
class Route
{
    /**
     * Default route delimiter
     */
    private const DEFAULT_DELIMITER = '/';

    /**
     * Default parameter group opening
     */
    private const PARAMETER_OPEN = '{';

    /**
     * Default parameter group closing
     */
    private const PARAMETER_CLOSE = '}';

    /**
     * Default regex delimiter
     */
    private const REGEX_DELIMITER = '/';

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var array
     */
    private $parameterGroup;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var string|null
     */
    private $pattern;

    /**
     * Route constructor.
     * @param string $route
     * @param Route|null $parent
     */
    public function __construct(string $route, ?Route $parent = null)
    {
        $this->route = $route;

        $this
            ->configureParameters(self::PARAMETER_OPEN, self::PARAMETER_CLOSE)
            ->dividedBy(self::DEFAULT_DELIMITER);

        if ($parent !== null) {
            $this->parameters = $parent->parameters;
            $this->setPath(array_merge($parent->getPath(), $this->getPath()));
        }
    }

    /**
     * @param array $parts
     * @return Route
     */
    private function setPath(array $parts): Route
    {
        $this->route = implode($this->delimiter, array_filter($parts, 'trim'));

        return $this;
    }

    /**
     * @return array
     */
    private function getPath(): array
    {
        return array_filter(explode($this->delimiter, $this->route), 'trim');
    }

    /**
     * @param string $route
     * @return Route
     */
    public static function new(string $route): Route
    {
        return new static($route);
    }

    /**
     * @param string $delimiter
     * @return Route
     */
    public function dividedBy(string $delimiter): Route
    {
        $this->reset();
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @return void
     */
    private function reset(): void
    {
        $this->pattern = null;
    }

    /**
     * @param string $open
     * @param string $close
     * @return Route
     */
    public function configureParameters(string $open, string $close): Route
    {
        $this->reset();

        $this->parameterGroup = [
            $open,
            $close,
        ];

        return $this;
    }

    /**
     * @param string $char
     * @param int $repeats
     * @return string
     */
    private function quote(string $char, int $repeats = 1): string
    {
        for ($i = 0; $i < $repeats; ++$i) {
            $char = preg_quote($char, self::REGEX_DELIMITER);
        }

        return $char;
    }

    /**
     * @param array $wheres
     * @return Route
     */
    public function whereArray(array $wheres): Route
    {
        foreach ($wheres as $name => $expr) {
            $this->where($name, $expr);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $rule
     * @return Route
     */
    public function where(string $name, string $rule): Route
    {
        $this->reset();
        $this->parameters[$name] = $rule;

        return $this;
    }

    /**
     * @param string $input
     * @return bool
     * @throws CompilerException
     */
    public function match(string $input): bool
    {
        $this->compileIfNotCompiled();

        $input = $this->filterInput($input);

        return (bool)preg_match(sprintf('/^%s$/isu', $this->pattern), $input);
    }

    /**
     * @param string $input
     * @return bool
     * @throws CompilerException
     */
    public function startsWith(string $input): bool
    {
        $this->compileIfNotCompiled();

        $input = $this->filterInput($input);

        return (bool)preg_match(sprintf('/^%s/isu', $this->pattern), $input);
    }

    /**
     * @param string $input
     * @return bool
     * @throws CompilerException
     */
    public function endsWith(string $input): bool
    {
        $this->compileIfNotCompiled();

        $input = $this->filterInput($input);

        return (bool)preg_match(sprintf('/%s$/isu', $this->pattern), $input);
    }

    /**
     * @param string $input
     * @return string
     * @throws CompilerException
     */
    private function filterInput(string $input): string
    {
        return rtrim($input, $this->delimiter) . $this->delimiter;
    }

    /**
     * @throws CompilerException
     */
    private function compileIfNotCompiled(): void
    {
        if ($this->pattern === null) {
            try {
                $this->pattern = $this->compile();
            } catch (\Throwable $e) {
                $error = 'Can not compile the Route: ' . $e->getMessage();
                throw new CompilerException($error, 0, $e);
            }
        }
    }

    /**
     * @return string
     * @throws IndeterminateBehaviorException
     */
    private function compile(): string
    {
        if (in_array($this->delimiter, $this->parameterGroup, true)) {
            throw IndeterminateBehaviorException::new(
                'The path separator "%s" conflicts with the definition of the parameter "%s...%s"',
                $this->delimiter,
                ...$this->parameterGroup
            );
        }

        $regex = sprintf('/%s(.*?)%s/isu',
            $this->quote($this->parameterGroup[0], 2),
            $this->quote($this->parameterGroup[1], 2)
        );

        $route = rtrim($this->route, $this->delimiter) . $this->delimiter;
        $route = $this->quote($route);

        return preg_replace_callback($regex, [$this, 'compileArgument'], $route);
    }

    /**
     * @param array $args
     * @return string
     */
    private function compileArgument(array $args): string
    {
        [$group, $argument] = $args;

        $regex = $this->parameters[$argument] ?? (
            '[^' . $this->quote($this->delimiter) . '].*?'
        );

        return sprintf('(?P<%s>%s)', $this->quote($argument), $regex);
    }
}
