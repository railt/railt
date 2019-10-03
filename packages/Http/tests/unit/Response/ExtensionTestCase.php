<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Response;

use Railt\Http\Extension\Extension;
use Railt\Http\Tests\Unit\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Extension\MutableExtensionInterface;

/**
 * Class ExtensionTestCase
 */
class ExtensionTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testName(): void
    {
        $extension = $this->extension('name');

        $this->assertSame('name', $extension->getName());
    }

    /**
     * @param string $name
     * @param null $value
     * @return MutableExtensionInterface
     */
    protected function extension(string $name, $value = null): MutableExtensionInterface
    {
        return new Extension($name, $value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testMutableName(): void
    {
        $extension = $this->extension('name');
        $extension->rename('test');

        $this->assertSame('test', $extension->getName());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testValue(): void
    {
        $extension = $this->extension('a', 42);

        $this->assertSame(42, $extension->getValue());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testMutableValue(): void
    {
        $extension = $this->extension('a', 42);
        $extension->update(23);

        $this->assertSame(23, $extension->getValue());
    }
}
