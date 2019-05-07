<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Discovery;

use Composer\Autoload\ClassLoader;
use Composer\Composer;
use Railt\Component\Io\File;
use Railt\Component\Json\Json;

/**
 * Class Discovery
 */
class Discovery
{
    /**
     * @var string
     */
    public const DISCOVERY_MANIFEST_FILENAME = 'discovery.json';

    /**
     * @var string
     */
    private $pathname;

    /**
     * @var array|null
     */
    private $data;

    /**
     * Discovery constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->pathname = $this->getInstallationPathname($path);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getInstallationPathname(string $path): string
    {
        return $path . \DIRECTORY_SEPARATOR . self::DISCOVERY_MANIFEST_FILENAME;
    }

    /**
     * @param Composer $composer
     * @return Discovery
     * @throws \RuntimeException
     */
    public static function fromComposer(Composer $composer): self
    {
        $path = $composer->getConfig()->get('vendor-dir');

        return new static($path);
    }

    /**
     * @return Discovery
     * @throws \ReflectionException
     */
    public static function fromClassLoader(): self
    {
        $reflection = new \ReflectionClass(ClassLoader::class);

        return new static(\dirname($reflection->getFileName(), 2));
    }

    /**
     * @return Discovery
     * @throws \ReflectionException
     */
    public static function auto(): self
    {
        return static::fromClassLoader();
    }

    /**
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     * @throws \Railt\Component\Io\Exception\NotReadableException
     */
    public function get(string $key, $default = null)
    {
        $array = $this->all();

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
     * @return array
     * @throws \Railt\Component\Io\Exception\NotReadableException
     */
    public function all(): array
    {
        if ($this->data === null) {
            $this->data = \is_file($this->pathname) ? Json::read(File::fromPathname($this->pathname)) : [];
        }

        return $this->data;
    }
}
