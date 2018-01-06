<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\GraphQL;

use Railt\GraphQL\Exceptions\TypeConflictException;
use Railt\GraphQL\Reflection\CompilerInterface;
use Railt\Reflection\Filesystem\File;

/**
 * Class EnumValidationTestCase
 */
class EnumValidationTestCase extends AbstractCompilerTestCase
{
    /**
     * @return array
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \LogicException
     */
    public function provider(): array
    {
        $result = [];

        foreach ($this->getCompilers() as $compiler) {
            $result[] = [$compiler];
        }

        return $result;
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testEnumCanNotBeEmpty(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources('enum A {}'));
    }
}
