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
use Serafim\Railgun\Exceptions\NotReadableException;
use Serafim\Railgun\Exceptions\SemanticException;
use Serafim\Railgun\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Exceptions\UnrecognizedTokenException;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
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
     *
     */
    public function testTypeRedefinition(): void
    {
        $this->expectException(SemanticException::class);
        $this->expectExceptionMessage('Can not register type named "A" as Object. ' .
            'Type "A" already registered as Interface');

        (new Compiler())->compile($this->file('err-redefinition.graphqls'));
    }

    /**
     *
     */
    public function testTypeNotFound(): void
    {
        $this->expectException(TypeNotFoundException::class);
        $this->expectExceptionMessage('Type "QueryType" not found and could not be loaded');

        (new Compiler())->compile($this->file('schema-1.graphqls'));
    }

    /**
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public function testTypeAutoloading(): void
    {
        $compiler = new Compiler();
        $compiler->getLoader()->dir($this->resource('/'));
        $document = $compiler->compile($this->file('schema-1.graphqls'));


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

        $compiler->getLoader()->dir($this->resource('/'));
        $compiler->getLoader()->dir($this->resource('/sub/'), true);

        $document = $compiler->compile($this->file('schema-1.graphqls'));

        $schema = $document->getSchema();

        $this->assertNotNull($schema);

        $this->assertEquals('QueryType', $schema->getQuery()->getName());
        $this->assertEquals('MutationType', $schema->getMutation()->getName());

        $interfaces = $schema->getQuery()->getInterfaces();
        $this->assertCount(0, $interfaces);
    }

    /**
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public function testFileWithoutSchema(): void
    {
        $compiler = new Compiler();
        $document = $compiler->compile($this->file('QueryType.graphqls'));

        $this->assertNull($document->getSchema());
    }

    /**
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public function testDictionary(): void
    {
        $compiler = new Compiler();
        $compiler->getLoader()->dir($this->resource('/'));
        $document = $compiler->compile($this->file('schema-1.graphqls'));

        $this->assertNotNull($document);

        $count = 0;
        /**
         * @var DocumentTypeInterface $ctx
         * @var DefinitionInterface $definition
         */
        foreach ($compiler->getDictionary() as $ctx => $definition) {
            if (!$ctx->isStdlib()) {
                $count++;
            }

            $this->assertInstanceOf(DocumentTypeInterface::class, $ctx);
            $this->assertInstanceOf(DefinitionInterface::class, $definition);
        }
        $this->assertEquals(5, $count);


        $count = 0;
        /**
         * @var DocumentTypeInterface $ctx
         * @var NamedDefinitionInterface $definition
         */
        foreach ($compiler->getDictionary()->named() as $ctx => $definition) {
            if (!$ctx->isStdlib()) {
                $count++;
            }

            $this->assertInstanceOf(DocumentTypeInterface::class, $ctx);
            $this->assertInstanceOf(NamedDefinitionInterface::class, $definition);
        }
        $this->assertEquals(4, $count);


        $count = 0;
        /**
         * @var DocumentTypeInterface $ctx
         * @var DefinitionInterface $definition
         */
        foreach ($compiler->getDictionary()->anonymous() as $ctx => $definition) {
            if (!$ctx->isStdlib()) {
                $count++;
            }

            $this->assertInstanceOf(DocumentTypeInterface::class, $ctx);
            $this->assertInstanceOf(DefinitionInterface::class, $definition);
            $this->assertNotInstanceOf(NamedDefinitionInterface::class, $definition);
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

        (new Compiler())->compile($this->file('unexpected.graphqls'));
    }

    /**
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     */
    public function testUnrecognizedToken(): void
    {
        $this->expectException(UnrecognizedTokenException::class);
        $this->expectExceptionMessage(
            'Unrecognized token "<" at line 1 and column 17:' . "\n" .
            'type Some {' . "\n" .
            '    <<<<<' . "\n" .
            '}' . "\n\n" .
            '                ↑'
        );

        (new Compiler())->compile($this->file('unrecognized.graphqls'));
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFileNotReadable(): void
    {
        $error = sprintf('File "%s" not readable. File not found.', $this->resource('nonexistent.file'));

        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage($error);

        (new Compiler())->compile($this->file('nonexistent.file'));
    }
}
