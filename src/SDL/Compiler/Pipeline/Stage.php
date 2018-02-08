<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

use Railt\Io\Readable;

/**
 * Interface Stage
 */
interface Stage
{
    /**
     * @param Readable $input
     * @param mixed $data
     * @return mixed
     */
    public function handle(Readable $input, $data);
}
