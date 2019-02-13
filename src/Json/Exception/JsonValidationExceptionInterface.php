<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Exception;

/**
 * Interface JsonValidationExceptionInterface
 */
interface JsonValidationExceptionInterface extends \Throwable
{
    /**
     * @param string $implode
     * @return string
     */
    public function getPathString(string $implode = '.'): string;

    /**
     * @return array
     */
    public function getPath(): array;
}
