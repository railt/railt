<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Tests\Unit;

use Railt\Container\Container;
use Railt\Container\Registrable;
use PHPUnit\Framework\Exception;
use Railt\Container\Autowireable;
use Railt\Container\ContainerInterface;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Container\ContainerInterface as PSRContainer;

/**
 * Class ContainerCompatibilityTestCase
 */
class ContainerCompatibilityTestCase extends TestCase
{
    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testPSRCompatibility(): void
    {
        $this->assertInstanceOf(PSRContainer::class, new Container());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testIsInterfaceCompatibility(): void
    {
        $this->assertInstanceOf(ContainerInterface::class, new Container());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testIsAutowireable(): void
    {
        $this->assertInstanceOf(Autowireable::class, new Container());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testIsRegistrable(): void
    {
        $this->assertInstanceOf(Registrable::class, new Container());
    }
}