<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Compiler;

use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;

/**
 * Class DocumentTestCase
 * @package Serafim\Railgun\Tests\Compiler
 */
class DocumentTestCase extends AbstractTestCase
{
    /**
     * @param string $file
     * @param bool $enableAutoloader
     * @return \Serafim\Railgun\Compiler\Document
     * @throws SemanticException
     * @throws UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     */
    private function mock(string $file = 'schema-1.graphqls', bool $enableAutoloader = true)
    {
        $compiler = new Compiler();

        $dir = __DIR__ . '/../.resources/reflection/';

        if ($enableAutoloader) {
            $compiler->getLoader()->autoload(function (string $queryType) use ($dir) {
                return $dir . $queryType . '.graphqls';
            });
        }

        return $compiler->parseFile($dir . $file);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeRedefinition(): void
    {
        $this->expectException(SemanticException::class);
        $this->expectExceptionMessage('Can not register type named "A" as Object. ' .
            'Type "A" already registered as Interface');

        $this->mock('err-redefinition.graphqls', false);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeNotFound(): void
    {
        $this->expectException(TypeNotFoundException::class);
        $this->expectExceptionMessage('Type "QueryType" not found and could not be loaded');

        $this->mock('schema-1.graphqls', false);
    }

    /**
     * @throws SemanticException
     * @throws UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     */
    public function testTypeLoading()
    {
        $document = $this->mock();
        $schema = $document->getSchema();

        $this->assertNotNull($schema);

        $this->assertEquals('QueryType', $schema->getQuery()->getName());
        $this->assertEquals('MutationType', $schema->getMutation()->getName());

        $interfaces = $schema->getQuery()->getInterfaces();
        $this->assertCount(2, $interfaces);
    }
}
