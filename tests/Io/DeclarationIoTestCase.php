<?php
/**
 * This file is part of Io package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\File;

/**
 * Class DeclarationTestCase
 */
class DeclarationIoTestCase extends AbstractIoTestCase
{
    /**
     * @return void
     */
    public function testDeclaration(): void
    {
        $a = File::fromPathname(__DIR__ . '/.resources/a.txt');

        $declaration = $a->getDeclaration();

        $this->assertSame(__LINE__ - 4, $declaration->getLine());
        $this->assertSame(__FILE__, $declaration->getPathname());
        $this->assertSame(__CLASS__, $declaration->getClass());
    }
}
