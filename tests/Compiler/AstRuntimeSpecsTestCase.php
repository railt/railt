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
use Railt\Compiler\Parser\Factory;
use Railt\Parser\Runtime;
use Railt\Reflection\Filesystem\File;
use Railt\Tests\Support\SpecSupport;
use Railt\Tests\Support\SpecTest;

/**
 * Class CompilerTestCase
 * @group large
 */
class AstRuntimeSpecsTestCase extends AbstractCompilerTestCase
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

        $compiled = __DIR__ . '/../../src/Railt/Compiler/Parser/CompiledSDLParser.php';

        if (\is_file($compiled)) {
            \unlink($compiled);
        }
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
        $compiler = new Factory();
        $runtime  = new Runtime(File::fromPathname(__DIR__ . '/../../src/Compiler/resources/grammar/sdl.pp'));
        $compiler->setRuntime($runtime);



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
