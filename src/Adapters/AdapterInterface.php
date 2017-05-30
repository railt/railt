<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters;

use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Http\ResponderInterface;

/**
 * Interface AdapterInterface
 * @package Serafim\Railgun\Adapters
 */
interface AdapterInterface extends ResponderInterface
{
    /**
     * @return bool
     */
    public static function isSupportedBy(): bool;

    /**
     * AdapterInterface constructor.
     * @param Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint);
}
