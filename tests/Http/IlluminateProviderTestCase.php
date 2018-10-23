<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use Illuminate\Http\Request;
use Railt\Http\Provider\IlluminateProvider;
use Railt\Http\Provider\ProviderInterface;

/**
 * Class IlluminateProviderTestCase
 */
class IlluminateProviderTestCase extends TestCase
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
        if ($body) {
            $_SERVER['CONTENT_TYPE'] = 'application/json';
            \file_put_contents(MOCK_PHP_INPUT_STREAM, $body);
        }

        $laravel = new Request($query, $request, [], [], [], $_SERVER, $body);

        return new IlluminateProvider($laravel);
    }
}
