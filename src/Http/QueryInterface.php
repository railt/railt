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
 * Interface QueryInterface
 */
interface QueryInterface
{
    /**
     * Query http (GET/POST) argument name passed by default
     */
    public const QUERY_ARGUMENT = 'query';

    /**
     * Variables http (GET/POST) argument name passed by default
     */
    public const VARIABLES_ARGUMENT = 'variables';

    /**
     * Operation http (GET/POST) argument name passed by default
     */
    public const OPERATION_ARGUMENT = 'operationName';

    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @return iterable
     */
    public function getVariables(): iterable;

    /**
     * @param string $name
     * @return mixed
     */
    public function getVariable(string $name);

    /**
     * @return string|null
     */
    public function getOperationName(): ?string;
}
