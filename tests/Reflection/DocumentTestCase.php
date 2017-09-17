<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Support\Exceptions\NotFoundException;
use Railt\Support\Exceptions\NotReadableException;
use Railt\Parser\Exceptions\UnexpectedTokenException;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Compiler;
use Railt\Reflection\Contracts\DefinitionInterface;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\TypeNotFoundException;
use Railt\Tests\AbstractTestCase;

/**
 * Class DocumentTestCase
 * @package Railt\Tests\Reflection
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
        $this->expectException(TypeConflictException::class);
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
     * @throws NotReadableException
     * @throws TypeConflictException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Reflection\Exceptions\UnrecognizedNodeException
     */
    public function testTypeAutoloading(): void
    {
        $compiler = new Compiler();
        $compiler->getAutoloader()->dir($this->resource('/'));
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

        $compiler->getAutoloader()->dir($this->resource('/'));
        $compiler->getAutoloader()->dir($this->resource('/sub/'), true);

        $document = $compiler->compile($this->file('schema-1.graphqls'));

        $schema = $document->getSchema();

        $this->assertNotNull($schema);

        $this->assertEquals('QueryType', $schema->getQuery()->getName());
        $this->assertEquals('MutationType', $schema->getMutation()->getName());

        $interfaces = $schema->getQuery()->getInterfaces();
        $this->assertCount(0, $interfaces);
    }

    /**
     * @throws NotReadableException
     * @throws TypeConflictException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     * @throws \Railt\Reflection\Exceptions\UnrecognizedNodeException
     */
    public function testFileWithoutSchema(): void
    {
        $compiler = new Compiler();
        $document = $compiler->compile($this->file('QueryType.graphqls'));

        $this->assertNull($document->getSchema());
    }

    /**
     * @throws NotReadableException
     * @throws TypeConflictException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Reflection\Exceptions\UnrecognizedNodeException
     */
    public function testDictionary(): void
    {
        $compiler = new Compiler();
        $compiler->getAutoloader()->dir($this->resource('/'));
        $document = $compiler->compile($this->file('schema-1.graphqls'));

        $this->assertNotNull($document);

        $count = 0;
        /**
         * @var DocumentInterface $ctx
         * @var DefinitionInterface $definition
         */
        foreach ($compiler->getDictionary() as $ctx => $definition) {
            if (!$ctx->isStdlib()) {
                $count++;
            }

            $this->assertInstanceOf(DocumentInterface::class, $ctx);
            $this->assertInstanceOf(DefinitionInterface::class, $definition);
        }
        $this->assertEquals(5, $count);


        $count = 0;
        /**
         * @var DocumentInterface $ctx
         * @var NamedDefinitionInterface $definition
         */
        foreach ($compiler->getDictionary()->named() as $ctx => $definition) {
            if (!$ctx->isStdlib()) {
                $count++;
            }

            $this->assertInstanceOf(DocumentInterface::class, $ctx);
            $this->assertInstanceOf(NamedDefinitionInterface::class, $definition);
        }
        $this->assertEquals(4, $count);


        $count = 0;
        /**
         * @var DocumentInterface $ctx
         * @var DefinitionInterface $definition
         */
        foreach ($compiler->getDictionary()->anonymous() as $ctx => $definition) {
            if (!$ctx->isStdlib()) {
                $count++;
            }

            $this->assertInstanceOf(DocumentInterface::class, $ctx);
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
    public function testFileNotFound(): void
    {
        $error = sprintf('File "%s" not found.', $this->resource('nonexistent.file'));

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($error);

        (new Compiler())->compile($this->file('nonexistent.file'));
    }
}
