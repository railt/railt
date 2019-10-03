<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Request;

use Railt\Http\Request;
use Railt\Http\Tests\Unit\TestCase;
use Railt\Http\Request\ProvidesQueryInterface;
use PHPUnit\Framework\ExpectationFailedException;

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
     * @return ProvidesQueryInterface
     */
    private function request(string $query): ProvidesQueryInterface
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
