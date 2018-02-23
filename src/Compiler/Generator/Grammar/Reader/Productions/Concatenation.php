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
 * Class Concatenation
 */
class Concatenation extends Definition
{
    /**
     * Concatenation constructor.
     */
    public function __construct()
    {
        parent::__construct(self::DEFAULT_RULE);
    }
}
