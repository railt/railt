<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Contracts;

use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Route;

/**
 * Interface RouterInterface
 */
interface RouterInterface
{
    /**
     * @param FieldDefinition $type
     * @param string|null $field
     * @return Route
     */
    public function route(FieldDefinition $type, string $field = null): Route;

    /**
     * @param FieldDefinition $type
     * @return bool
     */
    public function has(FieldDefinition $type): bool;

    /**
     * @param FieldDefinition $type
     * @return Route[]|iterable
     */
    public function get(FieldDefinition $type): iterable;

    /**
     * @param Route $route
     * @return Route
     */
    public function add(Route $route): Route;
}
