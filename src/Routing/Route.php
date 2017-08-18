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
    public const DEFAULT_DELIMITER = '.';

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
     * @var int
     */
    private static $lastId = 0;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $delimiter;

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
     * @var Route
     */
    private $parent;

    /**
     * Route constructor.
     * @param null|string $route
     * @param null|Route $parent
     */
    public function __construct(?string $route = null, ?Route $parent = null)
    {
        $this->id = self::$lastId++;

        $this->dividedBy(self::DEFAULT_DELIMITER);

        if ($route !== null) {
            $this->when($route);
        }

        if ($parent !== null) {
            $this->inside($parent);
        }
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
     * @param string $route
     * @return Route
     */
    public function when(string $route): Route
    {
        $this->reset();
        $this->route = trim($route, $this->delimiter);

        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->route !== null;
    }

    /**
     * @param Route $route
     * @return Route
     */
    public function inside(Route $route): Route
    {
        $this->reset();
        $this->parent = $route;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     * @throws CompilerException
     */
    public function match(string $input): bool
    {
        $this->compileIfNotCompiled();

        $input = $this->filterInput($input);

        return (bool)preg_match(sprintf('/^%s$/isu', $this->pattern), $input);
    }

    /**
     * @throws CompilerException
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     */
    private function compileIfNotCompiled(): void
    {
        if ($this->pattern === null) {
            try {
                if ($this->parent !== null) {
                    $this->parameters = array_merge($this->parent->parameters, $this->parameters);
                }
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
        if (in_array($this->delimiter, [self::PARAMETER_OPEN, self::PARAMETER_CLOSE], true)) {
            throw IndeterminateBehaviorException::new(
                'The path separator "%s" conflicts with the definition of the parameter "%s...%s"',
                $this->delimiter,
                self::PARAMETER_OPEN,
                self::PARAMETER_CLOSE
            );
        }

        $regex = sprintf('/%s(.*?)%s/isu',
            $this->quote(self::PARAMETER_OPEN, 2),
            $this->quote(self::PARAMETER_CLOSE, 2)
        );

        $route = $this->quote($this->getRoute() . $this->delimiter);

        return preg_replace_callback($regex, [$this, 'compileArgument'], $route);
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
     * @return string
     */
    public function getRoute(): string
    {
        if ($this->parent !== null) {
            $parts = array_merge($this->parent->getPath(), $this->getPath());

            return implode($this->delimiter, $parts);
        }

        return (string)$this->route;
    }

    /**
     * @return string
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     * @throws CompilerException
     */
    public function getPattern(): string
    {
        $this->compileIfNotCompiled();

        return $this->pattern;
    }

    /**
     * @return array
     */
    private function getPath(): array
    {
        return array_filter(explode($this->delimiter, $this->route), 'trim');
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
     * @param string $input
     * @return bool
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
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
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     * @throws CompilerException
     */
    public function endsWith(string $input): bool
    {
        $this->compileIfNotCompiled();

        $input = $this->filterInput($input);

        return (bool)preg_match(sprintf('/%s$/isu', $this->pattern), $input);
    }

    /**
     * @return array
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     * @throws \Railgun\Exceptions\CompilerException
     */
    public function __debugInfo(): array
    {
        $this->compileIfNotCompiled();

        return [
            'route'      => $this->getRoute(),
            'parameters' => $this->parameters,
            'pattern'    => $this->pattern,
        ];
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
