<?php

/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Tests;

use Railt\TypeSystem\Argument;

/**
 * Class ArgumentTestCase
 */
class ArgumentTestCase extends TestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testCreatable(): void
    {
        $argument = new Argument();

        $this->expectNotToPerformAssertions();
    }
}
