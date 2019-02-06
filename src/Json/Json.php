<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

/**
 * Class Json
 */
class Json
{
    /**
     * @var string
     */
    protected static $class = JsonObject::class;

    /**
     * @var JsonObject|null
     */
    protected static $instance;

    /**
     * Json constructor.
     */
    private function __construct()
    {
        // Not accessible
    }

    /**
     * @return void
     */
    private function __clone()
    {
        // Not accessible
    }

    /**
     * @return JsonObject|static
     */
    public static function make(): self
    {
        return self::$instance ?? static::new();
    }

    /**
     * @return JsonObject|static
     */
    public static function new(): Json
    {
        $class = self::$class;

        return static::setInstance(new $class());
    }

    /**
     * @param JsonObject|null $instance
     * @return JsonObject|null
     */
    public static function setInstance(JsonObject $instance = null): ?JsonObject
    {
        self::$instance = $instance;

        return self::$instance;
    }
}
