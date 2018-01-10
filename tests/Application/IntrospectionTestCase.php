<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Application;

use Railt\Foundation\Application;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\Io\File;

/**
 * Class IntrospectionTestCase
 */
class IntrospectionTestCase extends AbstractApplicationTestCase
{
    /**
     * @dataProvider provider
     *
     * @param Application $app
     * @return void
     */
    public function testObjectTypeIsResolvable(Application $app): void
    {
        /** @var Response $response */
        $response = $app->request(
            $this->query('type Query {}'),
            $this->introspection()
        );

        $response->debug(true);

        static::assertInstanceOf(ResponseInterface::class, $response);
        static::assertTrue($response->isSuccessful(), \print_r($response->getErrors(), true));
    }

    /**
     * @dataProvider provider
     *
     * @param Application $app
     * @return void
     */
    public function testInterfaceTypeIsResolvable(Application $app): void
    {
        /** @var Response $response */
        $response = $app->request(
            $this->query('type Query implements Example {} interface Example {}'),
            $this->introspection()
        );

        $response->debug(true);

        static::assertInstanceOf(ResponseInterface::class, $response);
        static::assertTrue($response->isSuccessful(), \print_r($response->getErrors(), true));
    }

    /**
     * @dataProvider provider
     *
     * @param Application $app
     * @return void
     */
    public function testDirectivesTypeIsResolvable(Application $app): void
    {
        /** @var Response $response */
        $response = $app->request(
            $this->query('type Query @some {} directive @some on FIELD | TYPE'),
            $this->introspection()
        );

        $response->debug(true);

        static::assertInstanceOf(ResponseInterface::class, $response);
        static::assertTrue($response->isSuccessful(), \print_r($response->getErrors(), true));
    }

    /**
     * @return RequestInterface
     */
    private function introspection(): RequestInterface
    {
        $file = File::fromPathname($this->resource('introspection.graphql'));

        return $this->request($file->getContents());
    }
}
