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
use Serafim\Railgun\Compiler\Document;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;

/**
 * Class DocumentTestCase
 * @package Serafim\Railgun\Tests\Compiler
 */
class DocumentTestCase extends AbstractTestCase
{
    /**
     * @param string $file
     * @return Document
     * @throws UnexpectedTokenException
     */
    private function mock(string $file = 'schema-1.graphqls'): Document
    {
        $compiler = new Compiler();

        return $compiler->parseFile(__DIR__ . '/../.resources/reflection/' . $file);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testTypeRedefinition(): void
    {
        $this->expectException(SemanticException::class);
        $this->expectExceptionMessage('Can not register type named "A" as Object. ' .
            'Type "A" already registered as Interface');

        $this->mock('err-redefinition.graphqls');
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testSimpleSchema(): void
    {
        $schema = $this->mock()->getSchema();

        $this->assertNotNull($schema);
    }
}
