<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Server;

use Railt\Http\Provider\DataProvider;
use Swoole\Http\Request;

/**
 * Class RequestProvider
 * @internal This is experimental functional. Do not use it in production!
 */
class SwooleProvider extends DataProvider
{
    /**
     * RequestProvider constructor.
     * @param Request $request
     */
    public function __construct($request)
    {
        parent::__construct((array)$request->get, (array)$request->post);

        $this->withBody((string)$request->rawcontent());
        $this->withContentType($request->header['content-type'] ?? null);
    }
}
