<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Tests\Http;

use Railgun\Http\Request;
use Railgun\Http\RequestInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyNativeRequest;

/**
 * Class SymfonyRequestTestCase
 * @package Railgun\Tests\Http
 */
class SymfonyRequestTestCase extends AbstractHttpRequestTestCase
{
    protected function request(string $body, bool $makeJson = true): RequestInterface
    {
        $request = SymfonyNativeRequest::createFromGlobals();

        if ($makeJson) {
            $request->headers->set('Content-Type', 'application/json');
        }

        (function () use ($body) {
            $this->content = $body;
        })->call($request);

        return Request::create($request);
    }
}
