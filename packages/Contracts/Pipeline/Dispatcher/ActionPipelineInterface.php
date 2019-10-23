<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Pipeline\Dispatcher;

use Railt\Contracts\Http\InputInterface;
use Railt\Contracts\Http\OutputInterface;
use Railt\Contracts\Pipeline\PipelineInterface;

/**
 * Interface ActionPipelineInterface
 */
interface ActionPipelineInterface extends PipelineInterface
{
    /**
     * @param InputInterface $input
     * @param ActionInterface $action
     * @return OutputInterface
     */
    public function send(InputInterface $input, ActionInterface $action): OutputInterface;
}
