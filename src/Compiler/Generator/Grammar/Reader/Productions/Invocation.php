<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

/**
 * Class Invocation
 */
class Invocation extends Rule
{
    /**
     * Invocation constructor.
     * @param string $rule
     */
    public function __construct(string $rule)
    {
        $this->name = $rule;
    }
}
