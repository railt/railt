<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Compiler;

/**
 * Class Loader
 */
class Loader extends Repository
{
    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * Loader constructor.
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }
}
