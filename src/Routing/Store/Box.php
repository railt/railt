<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Store;
use Railt\Routing\Route;

/**
 * Interface Box
 */
interface Box
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function getResponse();

    /**
     * @param Route $route
     * @param array $collection
     * @return Box
     */
    public static function rebuild(Route $route, array $collection): self;
}
