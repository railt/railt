<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Request;

/**
 * Class RequestTestCase
 */
class RequestTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRequestArrayble(): void
    {
        $request = new Request('query', ['vars'], 'operation');

        $this->assertEquals([
            Request::FIELD_QUERY          => 'query',
            Request::FIELD_VARIABLES      => [0 => 'vars'],
            Request::FIELD_OPERATION_NAME => 'operation',
        ], $request->toArray());
    }
}
