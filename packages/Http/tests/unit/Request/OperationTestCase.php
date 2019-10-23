<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Request;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Contracts\Http\Request\OperationNameInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Http\Request;
use Railt\Http\Tests\Unit\TestCase;

/**
 * Class OperationTestCase
 */
class OperationTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOperationEmpty(): void
    {
        $request = $this->request(null);

        $this->assertNull($request->getOperationName());
        $this->assertFalse($request->hasOperationName());
    }

    /**
     * @param string|null $operation
     * @return RequestInterface
     */
    private function request(?string $operation): OperationNameInterface
    {
        return new Request('', [], $operation);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOperation(): void
    {
        $request = $this->request('name');

        $this->assertSame('name', $request->getOperationName());
        $this->assertTrue($request->hasOperationName());
    }
}
