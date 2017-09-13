<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Autoloader;

use Illuminate\Support\Str;

/**
 * Class Directory
 * @package Railt\Reflection\Compiler\Autoloader
 */
class Directory implements Rule
{
    private const DEFAULT_FILE_EXTENSIONS = [
        '.graphqls',
        '.graphqle',
        '.graphql',
        '.gql',
    ];

    /**
     * @var array
     */
    private $extensions = self::DEFAULT_FILE_EXTENSIONS;

    /**
     * @var string[]
     */
    private $directories;

    /**
     * Directory constructor.
     * @param string[] ...$directories
     */
    public function __construct(string ...$directories)
    {
        $this->directories = $directories;
    }

    /**
     * @param string $extension
     * @return Directory
     */
    public function extension(string $extension): Directory
    {
        $this->extensions[] = $extension;

        return $this;
    }

    /**
     * @param string $type
     * @return null|string
     */
    public function __invoke(string $type): ?string
    {
        foreach ($this->directories as $directory) {
            if (! Str::endsWith($directory, '/')) {
                $directory .= '/';
            }

            foreach ($this->extensions as $extension) {
                $path = $directory . $type . $extension;

                if (is_file($path) && is_readable($path)) {
                    return $path;
                }
            }
        }

        return null;
    }
}
