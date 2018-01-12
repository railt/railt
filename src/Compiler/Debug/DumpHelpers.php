<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Debug;

/**
 * Trait DumpHelpers
 */
trait DumpHelpers
{
    /**
     * @var int
     */
    protected $initialIndention = 0;

    /**
     * @var int
     */
    protected $indention = 4;

    /**
     * @param string $text
     * @return string
     */
    protected function inline(string $text): string
    {
        return \str_replace(["\n", "\r", "\t"], ['\n', '', '\t'], $text);
    }

    /**
     * @param int $depth
     * @param int $initial
     * @return $this
     */
    public function indent(int $depth = 4, int $initial = 0)
    {
        $this->indention = $depth;
        $this->initialIndention = $initial;

        return $this;
    }

    /**
     * @param string $line
     * @param int $depth
     * @return string
     */
    protected function depth(string $line, int $depth = 0): string
    {
        $prefix = \str_repeat(' ', $depth * (
            $this->initialIndention + $this->indention
        ));

        return $prefix . $line . \PHP_EOL;
    }
}
