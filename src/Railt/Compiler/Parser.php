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
use Railt\Compiler\Parser\CompiledSDLParser;
use Railt\Compiler\Parser\SDLParser;
use Railt\Reflection\Filesystem\NotFoundException;

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
     * Optimised template
     */
    private const COMPILED_TEMPLATE = __DIR__ . '/resources/templates/optimised-parser.php';

    /**
     * Return all tokens
     * @return iterable|string[]
     */
    public function getTokens(): iterable
    {
        foreach ($this->parser->getTokens() as $namespace => $tokens) {
            foreach ((array)$tokens as $token => $pattern) {
                yield $namespace => \array_first(\explode(':', $token));
            }
        }
    }

    /**
     * @return iterable|\Hoa\Compiler\Llk\Rule\Rule[]
     */
    public function getRules(): iterable
    {
        return $this->parser->getRules();
    }

    /**
     * @throws CompilerException
     * @throws NotFoundException
     */
    public function __destruct()
    {
        if (! $this->hasOptimizedLayer()) {
            $this->compile();
        }
    }

    /**
     * @return void
     * @throws CompilerException
     * @throws NotFoundException
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
     * @throws NotFoundException
     * @throws CompilerException
     */
    private function compileSources(string $namespace, string $class): string
    {
        [$selfClass, $selfFunction] = [__CLASS__, __FUNCTION__];

        try {
            $sources = Llk::save(parent::createParser(), $class);
        } catch (Exception $fatal) {
            throw CompilerException::wrap($fatal);
        }

        try {
            \ob_start();
            require self::COMPILED_TEMPLATE;
            $result = \ob_get_contents();
            \ob_end_clean();

            return $result;
        } catch (\Throwable $e) {
            throw new NotFoundException('Can not build optimized parser because template not resolvable');
        }
    }

    /**
     * @return LlkParser
     * @throws CompilerException
     */
    protected function createParser(): LlkParser
    {
        if ($this->hasOptimizedLayer()) {
            return new CompiledSDLParser();
        }

        return parent::createParser();
    }

    /**
     * @return bool
     */
    private function hasOptimizedLayer(): bool
    {
        return \class_exists(CompiledSDLParser::class);
    }
}
