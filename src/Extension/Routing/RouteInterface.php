<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Routing;

use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;

/**
 * Interface RouteInterface
 */
interface RouteInterface
{
    /**
     * @param RequestInterface $request
     * @param InputInterface $input
     * @return bool
     */
    public function match(RequestInterface $request, InputInterface $input): bool;

    /**
     * @param \Closure $filter
     * @return RouteInterface
     */
    public function matches(\Closure $filter): self;

    /**
     * @param string $name
     * @return RouteInterface
     */
    public function whereType(string $name): self;

    /**
     * @param string $field
     * @return RouteInterface
     */
    public function whereField(string $field): self;

    /**
     * @param string $type
     * @return RouteInterface
     */
    public function wherePreferType(string $type): self;

    /**
     * @return string|null
     */
    public function getPreferType(): ?string;

    /**
     * @param string $operation
     * @return RouteInterface
     */
    public function whereOperation(string $operation): self;

    /**
     * @param string $queryType
     * @return RouteInterface
     */
    public function whereQueryType(string $queryType): self;

    /**
     * @param string $variable
     * @return RouteInterface
     */
    public function whereVariableExists(string $variable): self;

    /**
     * @return callable|mixed
     */
    public function getAction();

    /**
     * @return iterable
     */
    public function filters(): iterable;
}
