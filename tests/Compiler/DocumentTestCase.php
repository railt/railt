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
     * @throws UnexpectedTokenException
     */
    public function testTypeRedefinition(): void
    {
        $this->expectException(SemanticException::class);
        $this->expectExceptionMessage('Can not register type named "A" as Object. ' .
            'Type "A" already registered as Interface');

        (new Compiler())
            ->parseFile(__DIR__ . '/../.resources/reflection/err-redefinition.graphqls');
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeNotFound(): void
    {
        $this->expectException(TypeNotFoundException::class);
        $this->expectExceptionMessage('Type "QueryType" not found and could not be loaded');

        (new Compiler())
            ->parseFile(__DIR__ . '/../.resources/reflection/schema-1.graphqls');
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
        $compiler = new Compiler();

        $dir = __DIR__ . '/../.resources/reflection';

        $compiler->getLoader()->autoload(function (string $queryType) use ($dir) {
            return $dir . '/' . $queryType . '.graphqls';
        });

        $document = $compiler->parseFile($dir. '/schema-1.graphqls');

        $this->assertNotNull($document->getSchema());
    }
}
