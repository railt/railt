<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Io\Readable;

/**
 * Class Json
 * @method static array read(Readable $readable)
 * @method static Readable write(string $pathname, array $data)
 *
 * @method static array decode(string $json)
 * @method static bool hasDecodeOption(int $option)
 * @method static int getDecodeOptions()
 * @method static JsonDecoderInterface setDecodeOptions(int $options)
 * @method static JsonDecoderInterface withDecodeOptions(int $options)
 *
 * @method static string encode(array $data)
 * @method static bool hasEncodeOption(int $option)
 * @method static int getEncodeOptions()
 * @method static JsonEncoderInterface setEncodeOptions(int $options)
 * @method static JsonEncoderInterface withEncodeOptions(int $options)
 */
class Json
{
    /**
     * @var string|JsonInteractorInterface
     */
    protected static $class = JsonInteractor::class;

    /**
     * @var JsonInteractor|null
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
     * @return JsonInteractorInterface
     */
    public static function make(): JsonInteractorInterface
    {
        return self::$instance ?? static::new();
    }

    /**
     * @return JsonInteractorInterface
     */
    public static function new(): JsonInteractorInterface
    {
        $class = self::$class;

        return static::setInstance(new $class());
    }

    /**
     * @param JsonInteractorInterface|null $instance
     * @return JsonInteractorInterface|null
     */
    public static function setInstance(JsonInteractorInterface $instance = null): ?JsonInteractorInterface
    {
        self::$instance = $instance;

        return self::$instance;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments = [])
    {
        return static::make()->{$name}(...$arguments);
    }

    /**
     * @return void
     */
    private function __clone()
    {
        // Not accessible
    }
}
