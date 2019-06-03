<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Request;

use Railt\Http\Request;
use Railt\Tests\Http\TestCase;
use Railt\Http\Request\MutableQueryInterface;
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
        $this->assertEquals('query', $request->getQuery());
    }

    /**
     * @param string $query
     * @return MutableQueryInterface
     */
    private function request(string $query): MutableQueryInterface
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

        $this->assertEquals('query', $request->getQuery());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testQueryMutation(): void
    {
        $request = $this->request('a');
        $this->assertEquals('a', $request->getQuery());

        $request->withQuery('b');
        $this->assertEquals('b', $request->getQuery());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testQueryMutationTrim(): void
    {
        $request = $this->request(' a ');
        $this->assertEquals('a', $request->getQuery());

        $request = $request->withQuery(' b ');
        $this->assertEquals('b', $request->getQuery());
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
