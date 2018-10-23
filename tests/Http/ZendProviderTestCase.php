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
use Railt\Http\Provider\ZendHttpProvider;
use Zend\Http\Header\ContentType;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

/**
 * Class ZendProviderTestCase
 */
class ZendProviderTestCase extends TestCase
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
        $zend = new Request();
        $zend->setPost(new Parameters($request));
        $zend->setQuery(new Parameters($query));
        $zend->setContent($body);

        if ($body) {
            $headers = new Headers();
            $headers->addHeader(new ContentType('application/json'));
            $zend->setHeaders($headers);
        }

        return new ZendHttpProvider($zend);
    }
}
