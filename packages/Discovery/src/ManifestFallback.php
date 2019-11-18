<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery;

use Composer\Script\Event;

/**
 * Class ManifestFallback
 */
class ManifestFallback
{
    /**
     * @var array[]
     */
    protected const CONFIGURATION = [];

    /**
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     */
    public static function get(string $key, $default = null)
    {
        $array = static::CONFIGURATION;

        foreach (\explode('.', $key) as $segment) {
            $allowsNext = \is_array($array) && isset($array[$segment]) && \array_key_exists($segment, $array);

            if ($allowsNext) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * @param Event $event
     * @return void
     * @throws \Throwable
     */
    public static function discover(Event $event): void
    {
        Generator::generate($event);
    }
}
