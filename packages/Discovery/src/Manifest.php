<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery;

/**
 * This is a stub class: it is in place only for scenarios where Discovery
 * is installed with a `--no-scripts` flag, in which scenarios the Manifest
 * class is not being replaced.
 *
 * If you are reading this doc block section inside your `vendor/` dir, then
 * this means that Discovery didn't correctly install, and is in "fallback"
 * mode.
 */
class Manifest extends ManifestFallback
{
    /**
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     */
    public static function get(string $key, $default = null)
    {
        @\trigger_error('Discovery manifest was not generated correctly', \E_NOTICE);

        return parent::get($key, $default);
    }
}
