<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Hoa\Compiler\Exception;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser as LlkParser;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Exceptions\InitializationException;
use Railt\Compiler\Parser\CompiledSDLParser;
use Railt\Compiler\Parser\SDLParser;

/**
 * Class Parser
 */
class Parser extends SDLParser
{
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
     * @return bool
     */
    private function hasOptimizedLayer(): bool
    {
        return \class_exists(CompiledSDLParser::class);
    }

    /**
     * @return void
     * @throws CompilerException
     */
    public function compile(): void
    {
        $sources = $this->compileSources(self::COMPILED_NAMESPACE, self::COMPILED_CLASS);

        \file_put_contents(self::COMPILED_FILE, $sources);
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

    /**
     * @return LlkParser
     * @throws InitializationException
     */
    protected function createParser(): LlkParser
    {
        if ($this->hasOptimizedLayer()) {
            return new CompiledSDLParser();
        }

        return parent::createParser();
    }

    /**
     * @throws CompilerException
     */
    public function __destruct()
    {
        if (!$this->hasOptimizedLayer()) {
            $this->compile();
        }
    }
}
