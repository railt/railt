<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Yay\Engine;

/**
 * Class Schema
 * @package Serafim\Railgun
 */
class Schema
{
    /**
     * @
     */
    private const EXEC_SUFFIX = '<?php ';

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
     * @return void
     * @throws \RuntimeException
     */
    private function loadMacros(): void
    {
        foreach ($this->getMacros() as $file) {
            $this->loadFile($file);
        }
    }

    /**
     * @param string $file
     * @return string
     * @throws \RuntimeException
     */
    public function loadFile(string $file): string
    {
        return $this->load(new \SplFileInfo($file));
    }

    /**
     * @param \SplFileInfo $file
     * @return string
     * @throws \RuntimeException
     */
    public function load(\SplFileInfo $file): string
    {
        return $this->read($file, true);
    }

    /**
     * @param \SplFileInfo $file
     * @param bool $suffix
     * @return string
     * @throws \RuntimeException
     */
    private function read(\SplFileInfo $file, bool $suffix = false): string
    {
        if (!$file->isReadable()) {
            throw new \RuntimeException('Can not read source file ' . $file->getRealPath());
        }

        $source = @file_get_contents($file->getRealPath());

        $result = $this->engine->expand(($suffix ? self::EXEC_SUFFIX : '') . $source, $file->getRealPath());

        return substr($result, strlen(self::EXEC_SUFFIX));
    }

    /**
     * @return iterable|string[]
     */
    private function getMacros(): iterable
    {
        yield __DIR__ . '/../macros/macro.yay';
    }
}
