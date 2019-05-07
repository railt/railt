<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Exception;

use Railt\Discovery\Composer\Package;
use Railt\Discovery\Composer\Section;
use Railt\Json\Exception\JsonValidationExceptionInterface as E;

/**
 * Class ValidationException
 */
class ValidationException extends \LogicException
{
    /**
     * @param \Throwable $e
     * @param Package $package
     * @return static|$this
     */
    public static function fromException(\Throwable $e, Package $package): self
    {
        $message = \vsprintf('The %s configuration error: %s', [
            $package->getName(),
            $e->getMessage(),
        ]);

        return new static($message, $e->getCode(), $e);
    }

    /**
     * @param E $e
     * @param Package $package
     * @param Section $section
     * @return static|$this
     */
    public static function fromJsonException(E $e, Package $package, Section $section): self
    {
        $message = \vsprintf('An error has been detected in configuration of %s package: %s in "%s"', [
            $package->getName(),
            $e->getMessage(),
            self::getKeyPath($e, $section),
        ]);

        return new static($message, $e->getCode());
    }

    /**
     * @param E $e
     * @param Section $section
     * @return string
     */
    public static function getKeyPath(E $e, Section $section): string
    {
        $path = \trim($e->getPathString(), '.');
        $path = \sprintf('extra.%s.%s', $section->getName(), $path);

        return \trim($path, '.');
    }
}
