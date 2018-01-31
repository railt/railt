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
use Railt\Events\Listenable;
use Railt\SDL\Compiler;

/**
 * Class Notifier
 */
class TimeLogger implements Tool
{
    /**
     * @var Notifier
     */
    private $notifier;

    /**
     * @var float
     */
    private $boot;

    /**
     * TimeLogger constructor.
     * @param null|Dispatcher $dispatcher
     */
    public function __construct(?Dispatcher $dispatcher = null)
    {
        $this->notifier = new Notifier($dispatcher);
        $this->boot     = \microtime(true);
    }

    /**
     * @param string $event
     * @param \Closure $then
     * @return TimeLogger
     */
    public function listen(string $event, \Closure $then): Listenable
    {
        $this->notifier->listen($event, function (string $event, array $payload) use ($then): void {
            $then($event, $this->boot = \microtime(true) - $this->boot, $payload);

            $this->boot = \microtime(true);
        });

        return $this;
    }

    /**
     * @param Compiler $compiler
     * @return Tool
     */
    public function observe(Compiler $compiler): Tool
    {
        return $this->notifier->observe($compiler);
    }
}
