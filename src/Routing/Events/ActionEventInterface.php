<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Events;

/**
 * Interface ActionEventInterface
 */
interface ActionEventInterface
{
    /**
     * @return \Closure
     */
    public function getAction(): \Closure;

    /**
     * @param \Closure $action
     * @return ActionEventInterface
     */
    public function withAction(\Closure $action): self;

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @param string $name
     * @param mixed $value
     * @return ActionEventInterface
     */
    public function withArgument(string $name, $value): self;

    /**
     * @param array $arguments
     * @return ActionEventInterface
     */
    public function withArguments(array $arguments): self;

    /**
     * @return mixed
     */
    public function getResponse();

    /**
     * @param mixed $answer
     * @return ActionEventInterface
     */
    public function withResponse($answer): self;
}
