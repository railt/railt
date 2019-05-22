<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PsrProvider
 */
class PsrProvider extends DataProvider
{
    /**
     * PsrProvider constructor.
     *
     * @param ServerRequestInterface $request
     * @throws \RuntimeException
     */
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request->getQueryParams(), (array)$request->getParsedBody());

        $this->withBody($request->getBody()->getContents());
        $this->withContentType($request->getHeaderLine('Content-Type'));
    }
}
