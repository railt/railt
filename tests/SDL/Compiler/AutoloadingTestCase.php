<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Compiler;

use Railt\Component\Io\File;
use Railt\Component\SDL\Exceptions\TypeNotFoundException;
use Railt\Component\SDL\Schema\CompilerInterface;

/**
 * Class AutoloadingTestCase
 */
class AutoloadingTestCase extends AbstractCompilerTestCase
{
    /**
     * @return array
     * @throws \Exception
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
        static::assertSame('ExistingType', $query->getName());
        static::assertNotSame($document->getUniqueId(), $query->getDocument()->getUniqueId());
    }
}
