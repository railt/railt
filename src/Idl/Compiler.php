<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Idl;

use Yay\Engine;

/**
 * Class Schema
 * @package Serafim\Railgun\Idl
 */
class Compiler
{
    /**
     *
     */
    private const EXECUTION_SUFFIX = '<?php ';

    /**
     * @var Engine
     */
    private $engine;

    /**
     * Schema constructor.
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->engine = new Engine();
        $this->loadMacros();
    }

    /**
     * @throws \RuntimeException
     * @throws \Yay\Halt
     */
    private function loadMacros(): void
    {
        foreach ($this->getMacros() as $file) {
            $this->compile($file);
        }
    }

    /**
     * @param string $file
     * @return string
     * @throws \RuntimeException
     * @throws \Yay\Halt
     */
    public function compile(string $file): string
    {
        return $this->compileFile(new \SplFileInfo($file));
    }

    /**
     * @param \SplFileInfo $file
     * @return string
     * @throws \RuntimeException
     * @throws \Yay\Halt
     */
    private function compileFile(\SplFileInfo $file): string
    {
        $sources = $this->readFile($file);

        $result = $this->engine->expand(self::EXECUTION_SUFFIX . $sources, $file->getRealPath());

        return substr($result, strlen(self::EXECUTION_SUFFIX));
    }

    /**
     * @param \SplFileInfo $file
     * @return string
     * @throws \RuntimeException
     */
    private function readFile(\SplFileInfo $file): string
    {
        if (!$file->isReadable()) {
            throw new \RuntimeException('Can not read source file ' . $file->getRealPath());
        }

        return @file_get_contents($file->getRealPath());
    }

    /**
     * @return iterable|string[]
     */
    private function getMacros(): iterable
    {
        //yield __DIR__ . '/../../resources/processing/macro.yay';
        yield __DIR__ . '/../../resources/processing/grammar.yay';
    }
}
