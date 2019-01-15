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
 * Class BaseBox
 */
abstract class BaseBox implements Box
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $serialized;

    /**
     * @var Route
     */
    protected $route;

    /**
     * BaseBox constructor.
     * @param Route $route
     * @param mixed $data
     * @param mixed $serialized
     */
    public function __construct(Route $route, $data, $serialized)
    {
        $this->route = $route;
        $this->data = $data;
        $this->serialized = $serialized;
    }

    /**
     * @param Route $route
     * @param array $items
     * @return Box
     */
    public static function rebuild(Route $route, array $items): Box
    {
        $data = [];
        $serialized = [];

        /** @var ObjectBox $box */
        foreach ($items as $box) {
            $data[] = $box->getValue();
            $serialized[] = $box->getResponse();
        }

        return static::make($route, $data, $serialized);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->data;
    }

    /**
     * @param Route $route
     * @param mixed $original
     * @param mixed $serialized
     * @return Box
     */
    public static function make(Route $route, $original, $serialized): Box
    {
        return new static($route, $original, $serialized);
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->serialized;
    }
}
