<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

use Composer\Autoload\ClassLoader;
use Railt\Discovery\Discovery;

/**
 * Class Composer
 */
class Composer implements ConfigurationInterface
{
    /**
     * @var string
     */
    public const AUTOLOAD_NOT_FOUND_ERROR =
        'Could not find autoload.php file.' . \PHP_EOL .
        'You need to set up the project dependencies using Composer:' . \PHP_EOL . \PHP_EOL .
        '    composer install' . \PHP_EOL . \PHP_EOL .
        'You can learn all about Composer on https://getcomposer.org/.' . \PHP_EOL;

    /**
     * @var string[]
     */
    public const AUTOLOAD_PATHS = [
        __DIR__ . '/../../autoload.php',
        __DIR__ . '/../../../vendor/autoload.php',
        __DIR__ . '/../../vendor/autoload.php',
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/vendor/autoload.php',
    ];

    /**
     * @var array|string[]
     */
    private static $paths = self::AUTOLOAD_PATHS;

    /**
     * @var Discovery
     */
    private $discovery;

    /**
     * Composer constructor.
     * @param Discovery $discovery
     */
    public function __construct(Discovery $discovery)
    {
        $this->discovery = $discovery;
    }

    /**
     * @return \Railt\Foundation\Config\ConfigurationInterface
     * @throws \LogicException
     */
    public static function fromDiscovery(): ConfigurationInterface
    {
        return new static(static::getDiscovery());
    }

    /**
     * @param string $path
     * @return Composer|string
     */
    public static function addAutoloadPath(string $path): string
    {
        \array_unshift(self::$paths, $path);

        return self::class;
    }

    /**
     * @return Discovery
     * @throws \LogicException
     */
    public static function getDiscovery(): Discovery
    {
        return Discovery::fromClassLoader(self::getClassLoader());
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     * @throws \LogicException
     */
    public static function getClassLoader(): ClassLoader
    {
        /** @noinspection PhpIncludeInspection */
        return require self::resolvePath();
    }

    /**
     * @return string
     * @throws \LogicException
     */
    private static function resolvePath(): string
    {
        foreach (self::$paths as $file) {
            if (\is_file($file) && \is_readable($file)) {
                return $file;
            }
        }

        throw new \LogicException(self::AUTOLOAD_NOT_FOUND_ERROR);
    }

    /**
     * @return iterable
     * @throws \InvalidArgumentException
     */
    public function getExtensions(): iterable
    {
        return (array)$this->discovery->get('railt.extensions', []);
    }

    /**
     * @return iterable
     * @throws \InvalidArgumentException
     */
    public function getCommands(): iterable
    {
        return (array)$this->discovery->get('railt.commands', []);
    }
}
