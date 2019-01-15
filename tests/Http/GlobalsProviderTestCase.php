<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use Railt\Http\Provider\GlobalsProvider;
use Railt\Http\Provider\ProviderInterface;

/**
 * Class GlobalsProviderTestCase
 */
class GlobalsProviderTestCase extends TestCase
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
        $_GET = $query;
        $_POST = $request;

        $path = __DIR__ . '/.temp/input-stream.' . \random_int(1, \PHP_INT_MAX) . '.txt';
        \defined('MOCK_PHP_INPUT_STREAM') or \define('MOCK_PHP_INPUT_STREAM', $path);

        if ($body) {
            $_SERVER['CONTENT_TYPE'] = 'application/json';
            \file_put_contents(MOCK_PHP_INPUT_STREAM, $body);
        }

        return new class() extends GlobalsProvider {
            protected const PHP_INPUT_STREAM = MOCK_PHP_INPUT_STREAM;
        };
    }
}
