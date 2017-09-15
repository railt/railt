<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Support\Filesystem\File;
use Railt\Parser\Parser;
use Railt\Tests\AbstractTestCase;
use Railt\Tests\Support\SpecSupport;
use Railt\Tests\Support\SpecTest;

/**
 * Class CompilerTestCase
 * @package Railt\Tests\Parser\Compiler
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
     * @throws ExpectationFailedException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testLanguageAstParsing(SpecTest $spec): void
    {
        $compiler = new Parser();

        $ast = $compiler->parse(File::fromSources($spec->getIn(), $spec->getPath()));

        $dump = $compiler->dump($ast);

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
