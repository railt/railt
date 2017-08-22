<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use PHPUnit\Framework\Assert;
use Railt\Compiler\Compiler;
use Railt\Support\File;
use Railt\Tests\AbstractTestCase;
use Railt\Tests\Support\SpecTest;
use Railt\Tests\Support\SpecSupport;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class CompilerTestCase
 * @package Railt\Tests\Compiler
 * @group large
 */
class AstSpecsTestCase extends AbstractTestCase
{
    use SpecSupport;

    /**
     * @var string
     */
    protected $specDirectory = __DIR__ . '/../.resources/ast-spec-tests';

    /**
     * @dataProvider specProvider
     * @param SpecTest $spec
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     */
    public function testLanguageAstParsing(SpecTest $spec): void
    {
        $compiler = new Compiler();

        $ast = $compiler->parse(File::virual($spec->getIn(), $spec->getPath()));

        $dump = trim(dump($ast));

        try {
            $otherwise = 'Error in test "' . str_replace('"', "'", $spec->getName())
                . '" defined in ' . $spec->getPath();

            Assert::assertEquals($spec->getOut(), $dump, $otherwise);
        } catch (ExpectationFailedException $e) {
            echo $this->specDiff($spec, $dump);
            flush();
            throw $e;
        }
    }
}
