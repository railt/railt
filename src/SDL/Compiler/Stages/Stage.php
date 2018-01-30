<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Stages;

use Railt\Events\Observable;

/**
 * Interface Stage
 */
interface Stage extends Observable
{
    /**
     * @param object|mixed $data
     * @return object|mixed
     */
    public function resolve($data);
}
