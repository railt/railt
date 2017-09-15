<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Exception;
use Hoa\Compiler\Llk\Parser as LlkParser;
use Railt\Parser\Parser\SDLParser;
use Railt\Parser\Exceptions\CompilerException;
use Railt\Support\Debuggable;
use Railt\Parser\Parser\CompiledSDLParser;

/**
 * Class Parser
 * @package Railt\Parser
 */
class Parser extends SDLParser
{
    use Debuggable;

    /**
     * Compiled parser namespace
     */
    private const COMPILED_NAMESPACE = __NAMESPACE__ . '\\Parser';

    /**
     * Compiled parser class
     */
    private const COMPILED_CLASS = 'CompiledSDLParser';

    /**
     * Compiled file path
     */
    private const COMPILED_FILE = __DIR__ . '/Parser/CompiledSDLParser.php';

    /**
     * @return void
     * @throws CompilerException
     */
    public function compile(): void
    {
        $sources = $this->compileSources(self::COMPILED_NAMESPACE, self::COMPILED_CLASS);

        file_put_contents(self::COMPILED_FILE, $sources);
    }

    /**
     * @return LlkParser
     * @throws CompilerException
     * @throws Exception
     */
    protected function createParser(): LlkParser
    {
        if ($this->debug) {
            $this->compile();
        }

        if (class_exists(CompiledSDLParser::class)) {
            return new CompiledSDLParser();
        }

        return parent::createParser();
    }

    /**
     * @param string $namespace
     * @param string $class
     * @return string
     * @throws CompilerException
     */
    private function compileSources(string $namespace, string $class): string
    {
        $doc = '/** ' . PHP_EOL .
            ' * This is generated file. ' . PHP_EOL .
            ' * For update sources from grammar use %s::%s() method.' . PHP_EOL .
            ' */';

        $header = '<?php' . PHP_EOL .
            sprintf($doc, __CLASS__, __FUNCTION__) . PHP_EOL .
            'namespace ' . $namespace . ';' . PHP_EOL . PHP_EOL;

        try {
            $sources = Llk::save(parent::createParser(), $class);
        } catch (Exception $e) {
            throw new CompilerException($e->getMessage(), $e->getCode(), $e);
        }

        return $header . $sources;
    }
}
