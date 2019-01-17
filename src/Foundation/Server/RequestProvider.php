<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Server;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Http\Provider\DataProvider;

/**
 * Class RequestProvider
 * @internal This is experimental functional. Do not use it in production!
 */
class RequestProvider extends DataProvider
{
    /**
     * RequestProvider constructor.
     * @param ServerRequestInterface $request
     * @throws \RuntimeException
     */
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct((array)$request->getQueryParams(), (array)$request->getParsedBody());

        $this->withBody($request->getBody()->getContents());
        $this->withContentType($request->getHeaderLine('Content-Type'));
    }
}
