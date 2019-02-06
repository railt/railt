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
 * @method static array read(Readable $readable)
 * @method static Readable write(string $pathname, array $data)
 * @method static string encode(array $data)
 * @method static array decode(string $json)
 * @method static bool hasEncodeOption(int $option)
 * @method static bool hasDecodeOption(int $option)
 * @method static int getEncodeOptions()
 * @method static int getDecodeOptions()
 * @method static JsonInteractorInterface|JsonInteractor setEncodeOptions(int ...$options)
 * @method static JsonInteractorInterface|JsonInteractor setDecodeOptions(int ...$options)
 * @method static JsonInteractorInterface|JsonInteractor withEncodeOptions(int ...$options)
 * @method static JsonInteractorInterface|JsonInteractor withDecodeOptions(int ...$options)
 * @method static JsonInteractorInterface|JsonInteractor withoutDecodeOptions(int ...$options)
 * @method static JsonInteractorInterface|JsonInteractor withoutEncodeOptions(int ...$options)
 */
class Json
{
    /**
     * @var string
     */
    protected const DEFAULT_INTERACTOR_CLASS = JsonInteractor::class;

    /**
     * @var string|JsonInteractorInterface
     */
    protected static $interactor = self::DEFAULT_INTERACTOR_CLASS;

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments = [])
    {
        return static::new()->{$method}(...$arguments);
    }

    /**
     * @return JsonInteractorInterface|JsonInteractor
     */
    public static function new(): JsonInteractorInterface
    {
        return static::using(self::$interactor);
    }

    /**
     * @param string|JsonInteractorInterface $interactor
     * @return JsonInteractorInterface|JsonInteractor
     */
    public static function using(string $interactor): JsonInteractorInterface
    {
        \assert(\class_exists($interactor),
            \sprintf('Unable to find the given interactor class %s', $interactor));

        \assert(\is_subclass_of($interactor, JsonInteractorInterface::class),
            \sprintf('The given interactor must implement %s interface', JsonInteractorInterface::class));

        return new $interactor();
    }
}
