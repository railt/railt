<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * @var string
     */
    public const KEY_DEBUG = 'debug';

    /**
     * @var string
     */
    public const KEY_PRELOAD_FILES = 'preload.files';

    /**
     * @var string
     */
    public const KEY_PRELOAD_PATHS = 'preload.paths';

    /**
     * @var string
     */
    public const KEY_PRELOAD_EXTENSIONS = 'preload.extensions';

    /**
     * @var string
     */
    public const KEY_AUTOLOAD_FILES = 'autoload.files';

    /**
     * @var string
     */
    public const KEY_AUTOLOAD_PATHS = 'autoload.paths';

    /**
     * @var string
     */
    public const KEY_AUTOLOAD_EXTENSIONS = 'autoload.extensions';

    /**
     * @var string
     */
    public const KEY_COMMANDS = 'commands';

    /**
     * @var string
     */
    public const KEY_EXTENSIONS = 'extensions';

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null);
}
