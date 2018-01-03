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
 * Interface RequestInterface
 */
interface RequestInterface
{
    /**
     * Query http (GET/POST) argument name passed by default
     */
    public const DEFAULT_QUERY_ARGUMENT = 'query';

    /**
     * Variables http (GET/POST) argument name passed by default
     */
    public const DEFAULT_VARIABLES_ARGUMENT = 'variables';

    /**
     * Operation http (GET/POST) argument name passed by default
     */
    public const DEFAULT_OPERATION_ARGUMENT = 'operationName';

    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @return array
     */
    public function getVariables(): array;

    /**
     * @return null|string
     */
    public function getOperation(): ?string;

    /**
     * @param string $field
     * @param null $default
     * @return mixed
     */
    public function get(string $field, $default = null);

    /**
     * @param string $field
     * @return bool
     */
    public function has(string $field): bool;
}
