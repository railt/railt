<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Interface MessageInterface
 */
interface MessageInterface
{
    /**
     * @var string Data field name
     */
    public const FIELD_DATA = 'data';

    /**
     * @var string Errors field name
     */
    public const FIELD_ERRORS = 'errors';

    /**
     * @return iterable|\Throwable[]
     */
    public function getExceptions(): iterable;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @return bool
     */
    public function hasErrors(): bool;
}
