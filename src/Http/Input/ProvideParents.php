<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

use Railt\Http\InputInterface;

/**
 * Interface ProvideParents
 */
interface ProvideParents
{
    /**
     * @param int $depth
     * @return mixed
     */
    public function getParent(int $depth = 0);

    /**
     * @param int $depth
     * @return null|InputInterface
     */
    public function getParentInput(int $depth = 0): ?InputInterface;
}
