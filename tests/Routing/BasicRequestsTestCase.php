<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Routing;

use Railt\Foundation\ConnectionInterface;
use Railt\Component\Http\Request;
use Railt\Component\Io\File;

/**
 * Class BasicRequestsTestCase
 */
class BasicRequestsTestCase extends TestCase
{
    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleRequest(): void
    {
        $connection = $this->connect('
            schema {
                query: Query
            } 
            
            type Query { 
                scalar(value: String = null): String 
                    @route(action: "Railt\\\\Tests\\\\Routing\\\\Mock\\\\Controller@scalar")
            }
        ');

        $response = $connection->request(Request::create('{ scalar }'));

        $this->assertSame([], $response->getErrors());
        $this->assertSame(['scalar' => 'default'], $response->getData());

        $response = $connection->request(Request::create('{ scalar(value: "Value") }'));

        $this->assertSame([], $response->getErrors());
        $this->assertSame(['scalar' => 'Value'], $response->getData());
    }

    /**
     * @param string $schema
     * @return ConnectionInterface
     */
    protected function connect(string $schema): ConnectionInterface
    {
        return $this->app()->connect(File::fromSources($schema));
    }
}
