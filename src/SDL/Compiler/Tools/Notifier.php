<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Tools;

use Railt\Events\Dispatcher;
use Railt\Events\Events;
use Railt\Events\Listenable;
use Railt\SDL\Compiler;

/**
 * Class Notifier
 */
class Notifier implements Tool
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Notifier constructor.
     * @param Dispatcher|null $dispatcher
     */
    public function __construct(Dispatcher $dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?? new Events();
    }

    /**
     * @param string $event
     * @param \Closure $then
     * @return Notifier
     */
    public function listen(string $event, \Closure $then): Listenable
    {
        $this->dispatcher->listen($event, $then);

        return $this;
    }

    /**
     * @param Compiler $compiler
     * @return Notifier
     * @throws \LogicException
     */
    public function observe(Compiler $compiler): Tool
    {
        // TODO

        return $this;
    }
}
