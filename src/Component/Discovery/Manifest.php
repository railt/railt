<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Discovery;

use Composer\Composer;
use Composer\Script\Event;
use Railt\Component\Discovery\Exception\ValidationException;

/**
 * Class Manifest
 */
class Manifest
{
    /**
     * @param Event $event
     * @throws ValidationException
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public static function discover(Event $event): void
    {
        self::requireAutoloader($event->getComposer());

        $generator = new Generator($event->getComposer(), $event->getIO());
        $generator->save($generator->run());
    }

    /**
     * @param Composer $composer
     * @throws \RuntimeException
     */
    private static function requireAutoloader(Composer $composer): void
    {
        $vendor = $composer->getConfig()->get('vendor-dir');

        if (\is_file($vendor . '/autoload.php')) {
            require_once $vendor . '/autoload.php';
        }
    }
}
