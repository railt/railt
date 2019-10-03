<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit;

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

        $this->assertSame($request->getQuery(), 'query');
        $this->assertSame($request->getVariables(), [0 => 'vars']);
        $this->assertSame($request->getOperationName(), 'operation');
    }
}
