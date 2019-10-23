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
use Railt\Contracts\Http\Request\QueryInterface;
use Railt\Http\Request;
use Railt\Http\Tests\Unit\TestCase;

/**
 * Class QueryTestCase
 */
class QueryTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testQuerySelection(): void
    {
        $request = $this->request('query');
        $this->assertSame('query', $request->getQuery());
    }

    /**
     * @param string $query
     * @return QueryInterface
     */
    private function request(string $query): QueryInterface
    {
        return new Request($query);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testQueryTrim(): void
    {
        $request = $this->request('
            query
        ');

        $this->assertSame('query', $request->getQuery());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testQueryIsEmpty(): void
    {
        $this->assertTrue($this->request('')->isEmpty());
        $this->assertTrue($this->request('     ')->isEmpty());
        $this->assertFalse($this->request('value')->isEmpty());
    }
}
