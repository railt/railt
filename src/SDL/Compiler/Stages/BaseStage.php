<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Stages;

use Railt\Events\Observer;

/**
 * Class BaseStage
 */
abstract class BaseStage implements Stage
{
    use Observer;

    /**
     * @param mixed|object $data
     * @return mixed|object
     */
    public function resolve($data)
    {
        return $this->notify($data, true);
    }
}
