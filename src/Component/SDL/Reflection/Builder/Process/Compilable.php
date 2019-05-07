<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Builder\Process;

use Railt\Component\SDL\Schema\CompilerInterface;

/**
 * Interface Compilable
 */
interface Compilable
{
    /**
     * @return void
     */
    public function compile(): void;

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface;
}
