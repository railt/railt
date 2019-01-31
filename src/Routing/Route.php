<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;

/**
 * Class Route
 */
class Route implements RouteInterface
{
    /**
     * @var array|\Closure[]
     */
    private $filters = [];

    /**
     * @var callable|mixed
     */
    private $action;

    /**
     * @var string|null
     */
    private $preferType;

    /**
     * Route constructor.
     *
     * @param callable|mixed $action
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * @param string $name
     * @return RouteInterface
     */
    public function whereType(string $name): RouteInterface
    {
        return $this->matches(function (RequestInterface $_, InputInterface $input) use ($name): bool {
            return $input->getTypeName() === $name;
        });
    }

    /**
     * @param string $field
     * @return RouteInterface
     */
    public function whereField(string $field): RouteInterface
    {
        return $this->matches(function (RequestInterface $_, InputInterface $input) use ($field): bool {
            return $input->getField() === $field;
        });
    }

    /**
     * @param string $type
     * @return RouteInterface
     */
    public function wherePreferType(string $type): RouteInterface
    {
        $this->preferType = $type;

        return $this->matches(function (RequestInterface $_, InputInterface $input) use ($type): bool {
            return $input->wantsType($type);
        });
    }

    /**
     * @return string|null
     */
    public function getPreferType(): ?string
    {
        return $this->preferType;
    }

    /**
     * @param string $operation
     * @return RouteInterface
     */
    public function whereOperation(string $operation): RouteInterface
    {
        return $this->matches(function (RequestInterface $request) use ($operation): bool {
            return $request->getOperation() === $operation;
        });
    }

    /**
     * @param string $queryType
     * @return RouteInterface
     */
    public function whereQueryType(string $queryType): RouteInterface
    {
        return $this->matches(function (RequestInterface $request) use ($queryType): bool {
            return $request->getQueryType() === $queryType;
        });
    }

    /**
     * @param string $variable
     * @return RouteInterface
     */
    public function whereVariableExists(string $variable): RouteInterface
    {
        return $this->matches(function (RequestInterface $request) use ($variable): bool {
            return $request->hasVariable($variable);
        });
    }

    /**
     * @param \Closure $filter
     * @return RouteInterface
     */
    public function matches(\Closure $filter): RouteInterface
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @param InputInterface $input
     * @return bool
     */
    public function match(RequestInterface $request, InputInterface $input): bool
    {
        foreach ($this->filters as $filter) {
            if (! $filter($request, $input)) {
                return false;
            }
        }

        return \count($this->filters) !== 0;
    }

    /**
     * @return callable|mixed
     */
    public function getAction()
    {
        return $this->action;
    }
}
