<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Routing\Events;

use Railt\Http\Identifiable;
use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;
use Railt\Json\Json;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BaseActionEvent
 */
abstract class BaseActionEvent extends Event implements ActionEventInterface
{
    /**
     * @var callable|mixed
     */
    private $action;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var mixed
     */
    private $response;

    /**
     * @var bool
     */
    private $hasResponse = false;

    /**
     * BaseActionEvent constructor.
     *
     * @param callable|mixed $action
     * @param array $arguments
     */
    public function __construct($action, array $arguments = [])
    {
        $this->action = $action;
        $this->withArguments($arguments);
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->arguments[InputInterface::class];
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->arguments[RequestInterface::class];
    }

    /**
     * @return Identifiable
     */
    public function getConnection(): Identifiable
    {
        return $this->arguments[Identifiable::class];
    }

    /**
     * @return callable|mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param callable|mixed $action
     * @return ActionEventInterface
     */
    public function withAction($action): ActionEventInterface
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return ActionEventInterface
     */
    public function withArgument(string $name, $value): ActionEventInterface
    {
        return $this->withArguments([$name => $value]);
    }

    /**
     * @param array $arguments
     * @return ActionEventInterface
     */
    public function withArguments(array $arguments): ActionEventInterface
    {
        $this->arguments = \array_merge($this->arguments, $arguments);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->hasResponse;
    }

    /**
     * @param mixed $answer
     * @return ActionEventInterface
     */
    public function withResponse($answer): ActionEventInterface
    {
        $this->hasResponse = true;
        $this->response = $answer;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return Json::encode($this->getResponse());
        } catch (\JsonException $e) {
            return '';
        }
    }
}
