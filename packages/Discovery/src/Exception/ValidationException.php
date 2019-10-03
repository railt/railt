<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Exception;

/**
 * Class ValidationException
 */
class ValidationException extends ConfigurationException
{
    /**
     * ValidationException constructor.
     *
     * @param string $message
     * @param string $package
     * @param string $path
     */
    public function __construct(string $message, string $package, string $path)
    {
        $location = \trim(\vsprintf('extra.%s.%s', [
            $package,
            \str_replace('/', '.', \trim($path, '/')),
        ]), '.');

        parent::__construct($message . ' in [' . $location . ']');
    }
}
