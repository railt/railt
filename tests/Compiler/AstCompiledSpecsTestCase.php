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
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Parser;
use Railt\Reflection\Filesystem\File;
use Railt\Tests\Support\SpecSupport;
use Railt\Tests\Support\SpecTest;

/**
 * Class CompilerTestCase
 * @group large
 */
class AstCompiledSpecsTestCase extends AbstractCompilerTestCase
{
    use SpecSupport;

    /**
     * @var string
     */
    protected $specDirectory = __DIR__ . '/.resources/ast-spec-tests';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider specProvider
     *
     * @param SpecTest $spec
     * @throws ExpectationFailedException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function testRuntimeLanguageAstParsing(SpecTest $spec): void
    {
        $compiler = new Parser(new CallStack());
        $compiler->compile();

        $ast = $compiler->parse(File::fromSources($spec->getIn(), $spec->getPath()));

        $dump = $compiler->dump($ast);

        try {
            $otherwise = 'Error in test "' . \str_replace('"', "'", $spec->getName())
                . '" defined in ' . $spec->getPath();

            Assert::assertEquals($spec->getOut(), $dump, $otherwise);
        } catch (ExpectationFailedException $e) {
            echo $this->specDiff($spec, $dump);
            \flush();
            throw $e;
        }
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function provider(): array
    {
        return $this->specProvider();
    }
}
