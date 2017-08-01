<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Reflection;

use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Compiler\Document;
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Compiler\Reflection\Definition\Definition;
use Serafim\Railgun\Compiler\Reflection\Definition\SchemaDefinition;
use Serafim\Railgun\Tests\AbstractTestCase;

/**
 * Class CompilerTestCase
 * @package Serafim\Railgun\Tests\Reflection
 */
class DocumentTestCase extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $resourcesPath = 'document-tests/';

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeRedefinition(): void
    {
        $this->expectException(SemanticException::class);
        $this->expectExceptionMessage('Can not register type named "A" as Object. ' .
            'Type "A" already registered as Interface');

        (new Compiler())->compileFile($this->resource('err-redefinition.graphqls'));
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeNotFound(): void
    {
        $this->expectException(TypeNotFoundException::class);
        $this->expectExceptionMessage('Type "QueryType" not found and could not be loaded');

        (new Compiler())->compileFile($this->resource('schema-1.graphqls'));
    }

    /**
     * @throws SemanticException
     * @throws UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     */
    public function testTypeAutoloading(): void
    {
        $compiler = new Compiler();
        $compiler->getLoader()->psr0($this->resource('/'));
        $document = $compiler->compileFile($this->resource('schema-1.graphqls'));


        $schema = $document->getSchema();

        $this->assertNotNull($schema);

        $this->assertEquals('QueryType', $schema->getQuery()->getName());
        $this->assertEquals('MutationType', $schema->getMutation()->getName());

        $interfaces = $schema->getQuery()->getInterfaces();
        $this->assertCount(2, $interfaces);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeAutoloadingPrepend(): void
    {
        $compiler = new Compiler();

        $compiler->getLoader()->psr0($this->resource('/'));
        $compiler->getLoader()->psr0($this->resource('/sub/'), true);

        $document = $compiler->compileFile($this->resource('schema-1.graphqls'));

        $schema = $document->getSchema();

        $this->assertNotNull($schema);

        $this->assertEquals('QueryType', $schema->getQuery()->getName());
        $this->assertEquals('MutationType', $schema->getMutation()->getName());

        $interfaces = $schema->getQuery()->getInterfaces();
        $this->assertCount(0, $interfaces);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFileWithoutSchema(): void
    {
        $compiler = new Compiler();
        $document = $compiler->compileFile($this->resource('QueryType.graphqls'));

        $this->assertNull($document->getSchema());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testDictionary(): void
    {
        $compiler = new Compiler();
        $compiler->getLoader()->psr0($this->resource('/'));
        $document = $compiler->compileFile($this->resource('schema-1.graphqls'));

        $this->assertNotNull($document);

        $count = 0;
        foreach ($compiler->getDictionary()->all() as $ctx => $definition) {
            $count++;
            $this->assertInstanceOf(Document::class, $ctx);
            $this->assertInstanceOf(Definition::class, $definition);
        }
        $this->assertEquals(5, $count);


        $count = 0;
        foreach ($compiler->getDictionary()->allNamed() as $ctx => $definition) {
            $count++;
            $this->assertInstanceOf(Document::class, $ctx);
            $this->assertInstanceOf(Definition::class, $definition);
            $this->assertNotNull($definition->getName());
        }
        $this->assertEquals(4, $count);


        $count = 0;
        foreach ($compiler->getDictionary()->allAnonymous() as $ctx => $definition) {
            $count++;
            $this->assertInstanceOf(Document::class, $ctx);
            $this->assertInstanceOf(SchemaDefinition::class, $definition);
            $this->assertNull($definition->getName());
        }
        $this->assertEquals(1, $count);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testUnexpectedToken(): void
    {
        $this->expectException(UnexpectedTokenException::class);
        $this->expectExceptionMessage(
            'Unexpected token "Some" (T_NAME) at line 1 and column 8:' . "\n" .
            'schema Some {' . "\n" .
            '       ↑'
        );

        (new Compiler())->compileFile($this->resource('unexpected.graphqls'));
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testUnrecognizedToken(): void
    {
        $this->expectException(UnexpectedTokenException::class);
        $this->expectExceptionMessage(
            'Unrecognized token "<" at line 1 and column 17:' . "\n" .
            'type Some {' . "\n" .
            '    <<<<<' . "\n" .
            '}' . "\n\n" .
            '                ↑'
        );

        (new Compiler())->compileFile($this->resource('unrecognized.graphqls'));
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFileNotReadable(): void
    {
        $error = sprintf('File "%s" not exists or not readable', $this->resource('nonexistent.file'));

        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage($error);

        (new Compiler())->compileFile($this->resource('nonexistent.file'));
    }
}
