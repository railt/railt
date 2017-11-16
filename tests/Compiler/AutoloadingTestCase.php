<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Reflection\Filesystem\File;

/**
 * Class AutoloadingTestCase
 */
class AutoloadingTestCase extends AbstractCompilerTestCase
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
    public function testExceptionWhileTypeNotFound(CompilerInterface $compiler): void
    {
        $this->expectException(TypeNotFoundException::class);

        $document = $compiler->compile(File::fromSources('schema { query: MissingType }'));

        $document->getSchema()->getQuery();
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeLoading(CompilerInterface $compiler): void
    {
        $compiler->autoload(function (string $type) {
            return File::fromSources('type ExistingType {}');
        });

        $document = $compiler->compile(File::fromSources('schema { query: ExistingType }'));

        $query = $document->getSchema()->getQuery();

        static::assertNotNull($query);
        static::assertEquals('ExistingType', $query->getName());
        static::assertNotEquals($document->getUniqueId(), $query->getDocument()->getUniqueId());
    }
}
