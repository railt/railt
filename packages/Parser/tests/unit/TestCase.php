<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Tests\Unit;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Parser\Generator\Generator;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function setUp(): void
    {
        $generator = new Generator();

        $generator->generateAndSave();
    }
}
