<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support\Log;
use Railt\Support\Str;

/**
 * Class Level
 * @package Railt\Support\Log
 */
abstract class Level
{
    /**
     * @var array
     */
    private static $constants = [];

    /**
     * Detailed debug information
     */
    public const DEBUG = 100;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    public const INFO = 200;

    /**
     * Uncommon events
     */
    public const NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    public const WARNING = 300;

    /**
     * Runtime errors
     */
    public const ERROR = 400;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    public const CRITICAL = 500;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    public const ALERT = 550;

    /**
     * Urgent alert.
     */
    public const EMERGENCY = 600;

    /**
     * @return array
     */
    private static function getConstants(): array
    {
        if (count(self::$constants) === 0) {
            try {
                $reflection = new \ReflectionClass(self::class);
                self::$constants = $reflection->getConstants();
            } catch (\ReflectionException $e) {
                return [];
            }
        }

        return self::$constants;
    }

    /**
     * @param int $level
     * @return string
     */
    public static function toString(int $level): string
    {
        foreach (self::getConstants() as $name => $value) {
            if ($level === $value) {
                return Str::camelCase($name);
            }
        }

        return 'Debug';
    }
}
