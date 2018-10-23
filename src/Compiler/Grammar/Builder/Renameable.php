<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

use Railt\Compiler\Parser\Rule\Production;

/**
 * Trait Renameable
 */
trait Renameable
{
    /**
     * @param string $name
     * @return Production
     */
    public function rename(?string $name): Production
    {
        $this->kept = \is_string($name);
        $this->name = $name;

        return $this;
    }
}
