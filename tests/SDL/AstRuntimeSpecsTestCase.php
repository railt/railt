<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Compiler\Runtime;
use Railt\Io\File;
use Railt\SDL\Parser\Factory;
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
     * @dataProvider specProvider
     *
     * @param SpecTest $spec
     * @throws ExpectationFailedException
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function testRuntimeLanguageAstParsing(SpecTest $spec): void
    {
        $compiler = new Factory();
        $runtime  = new Runtime(File::fromPathname(__DIR__ . '/../../src/SDL/resources/grammar/sdl.pp'));
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
