<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Request;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Request;
use Railt\Http\Request\MutableOperationNameInterface;
use Railt\Tests\Http\TestCase;

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
     * @return MutableOperationNameInterface
     */
    private function request(?string $operation): MutableOperationNameInterface
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

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOperationChange(): void
    {
        $request = $this->request('name');
        $request->withOperation('some');

        $this->assertSame('some', $request->getOperationName());
    }
}
