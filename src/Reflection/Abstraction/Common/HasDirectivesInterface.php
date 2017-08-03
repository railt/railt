<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction\Common;

use Serafim\Railgun\Reflection\Abstraction\CalleeDirectiveInterface;

/**
 * Interface HasDirectivesInterface
 * @package Serafim\Railgun\Reflection\Abstraction\Common
 */
interface HasDirectivesInterface
{
    /**
     * @return iterable|CalleeDirectiveInterface[]
     */
    public function getDirectives(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;

    /**
     * @param string $name
     * @return null|CalleeDirectiveInterface
     */
    public function getDirective(string $name): ?CalleeDirectiveInterface;
}
