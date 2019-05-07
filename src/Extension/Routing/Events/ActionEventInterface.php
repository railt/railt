<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Routing\Events;

/**
 * Interface ActionEventInterface
 */
interface ActionEventInterface
{
    /**
     * @return callable|mixed
     */
    public function getAction();

    /**
     * @param callable|mixed $action
     * @return ActionEventInterface
     */
    public function withAction($action): self;

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
