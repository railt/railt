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

        $this->assertEquals(__LINE__ - 4, $declaration->getLine());
        $this->assertEquals(__FILE__, $declaration->getPathname());
        $this->assertEquals(__CLASS__, $declaration->getClass());
    }
}
