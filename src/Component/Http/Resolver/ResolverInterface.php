<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Resolver;

use Railt\Component\Http\Provider\ProviderInterface;
use Railt\Component\Http\RequestInterface;

/**
 * Interface ResolverInterface
 */
interface ResolverInterface
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
     * @param ProviderInterface $provider
     * @return iterable|RequestInterface[]
     */
    public function parse(ProviderInterface $provider): iterable;
}
