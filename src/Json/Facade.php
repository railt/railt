<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Phplrt\Io\Readable;

/**
 * @method static string encode(mixed $data, int $options = null)
 * @method static Readable write(string $pathname, array $data)
 * @method static bool hasEncodeOption(int $option)
 * @method static int getEncodeOptions()
 * @method static JsonEncoderInterface setEncodeOptions(int ...$options)
 * @method static JsonEncoderInterface withEncodeOptions(int ...$options)
 * @method static JsonEncoderInterface withoutEncodeOptions(int ...$options)
 *
 * @method static mixed decode(string $json, int $options = null)
 * @method static array read(Readable $readable)
 * @method static bool hasDecodeOption(int $option)
 * @method static int getDecodeOptions()
 * @method static JsonDecoderInterface setDecodeOptions(int ...$options)
 * @method static JsonDecoderInterface withDecodeOptions(int ...$options)
 * @method static JsonDecoderInterface withoutDecodeOptions(int ...$options)
 */
abstract class Facade implements JsonFacadeInterface
{
    /**
     * @var array|string[]
     */
    private static $encoderMethods = [];

    /**
     * @var array|string[]
     */
    private static $decoderMethods = [];

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public static function __callStatic(string $method, array $arguments = [])
    {
        self::bootIfNotBooted();

        if (\in_array($method, self::$encoderMethods, true)) {
            return static::encoder()->{$method}(...$arguments);
        }

        if (\in_array($method, self::$decoderMethods, true)) {
            return static::decoder()->{$method}(...$arguments);
        }

        $error = 'Method %s not found or not accessible';
        throw new \BadMethodCallException(\sprintf($error, $method));
    }

    /**
     * @return void
     */
    private static function bootIfNotBooted(): void
    {
        self::bootEncoderMethods();
        self::bootDecoderMethods();
    }

    /**
     * @return void
     */
    private static function bootEncoderMethods(): void
    {
        if (\count(self::$encoderMethods) === 0) {
            self::$encoderMethods = self::methods(JsonEncoderInterface::class);
        }
    }

    /**
     * @param string $interface
     * @return array|string[]
     */
    private static function methods(string $interface): array
    {
        \assert(\interface_exists($interface), \sprintf('Given interface %s not found', $interface));

        try {
            $reflection = new \ReflectionClass($interface);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

            return \array_map(function (\ReflectionMethod $method): string {
                return $method->getName();
            }, $methods);
        } catch (\ReflectionException $e) {
            return [];
        }
    }

    /**
     * @return void
     */
    private static function bootDecoderMethods(): void
    {
        if (\count(self::$decoderMethods) === 0) {
            self::$decoderMethods = self::methods(JsonDecoderInterface::class);
        }
    }
}
