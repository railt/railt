<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Inheritance\TypeVerification;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;

/**
 * Interface Verifier
 */
interface Verifier
{
    /**
     * @param bool $throws
     * @return Verifier
     */
    public function throwsOnError(bool $throws = true): Verifier;

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function match(AllowsTypeIndication $a, AllowsTypeIndication $b): bool;

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool;
}
