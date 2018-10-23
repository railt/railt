<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use Railt\Http\Provider\ProviderInterface;
use Railt\Http\Provider\PsrHttpProvider;
use Zend\Diactoros\CallbackStream;
use Zend\Diactoros\ServerRequest;

/**
 * Class PsrHttpProviderTestCase
 */
class PsrHttpProviderTestCase extends TestCase
{
    /**
     * @param array $query
     * @param array $request
     * @param string $body
     * @return ProviderInterface
     * @throws \Exception
     */
    protected function provider(array $query = [], array $request = [], string $body = ''): ProviderInterface
    {
        $psr = new ServerRequest();

        if ($query !== []) {
            $psr = $psr->withQueryParams($query);
        }

        if ($request !== []) {
            $psr = $psr->withParsedBody($request);
        }

        if ($body) {
            $stream = new CallbackStream(function () use ($body) {
                return $body;
            });

            $psr = $psr->withHeader('Content-Type', 'application/json')->withBody($stream);
        }

        return new PsrHttpProvider($psr);
    }
}
