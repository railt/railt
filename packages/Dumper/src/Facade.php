<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Dumper;

/**
 * Class Facade
 */
class Facade
{
    /**
     * @var TypeDumperInterface|null
     */
    private static ?TypeDumperInterface $instance = null;

    /**
     * @return TypeDumperInterface
     */
    public static function getInstance(): TypeDumperInterface
    {
        return self::$instance ?? self::$instance = new TypeDumper();
    }

    /**
     * @param TypeDumperInterface|null $dumper
     * @return TypeDumperInterface|null
     */
    public static function setInstance(?TypeDumperInterface $dumper): ?TypeDumperInterface
    {
        return self::$instance = $dumper;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function dump($value): string
    {
        return self::getInstance()->dump($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function type($value): string
    {
        return self::getInstance()->type($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function value($value): string
    {
        return self::getInstance()->value($value);
    }
}
