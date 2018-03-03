<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Contracts;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Routing\Route;

/**
 * Interface RouterInterface
 */
interface RouterInterface
{
    /**
     * @param TypeDefinition $type
     * @param string|null $field
     * @return Route
     */
    public function route(TypeDefinition $type, string $field = null): Route;

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function has(TypeDefinition $type): bool;

    /**
     * @param TypeDefinition $type
     * @return Route[]|iterable
     */
    public function get(TypeDefinition $type): iterable;

    /**
     * @param Route $route
     * @return Route
     */
    public function add(Route $route): Route;
}
