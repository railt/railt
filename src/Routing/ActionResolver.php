<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Adapters\Event;
use Railt\Events\Dispatcher;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;

/**
 * Class ActionResolver
 */
class ActionResolver
{
    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * Route result.
     *
     * @var Store
     */
    private $resultStore;

    /**
     * Resolver result.
     *
     * @var Store
     */
    private $responseStore;

    /**
     * ActionResolver constructor.
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;

        $this->resultStore   = new Store();
        $this->responseStore = new Store();
    }

    /**
     * @param FieldDefinition $field
     * @param Route $route
     * @param InputInterface $input
     * @param array $parameters
     * @return mixed Response data
     * @throws \RuntimeException
     */
    public function call(FieldDefinition $field, Route $route, InputInterface $input, array $parameters)
    {
        $this->withParentValue($input);

        // Update action parameters
        $parameters = $this->resolving($field, $route, $parameters);

        // Before serializing
        $result = $route->call($parameters);

        if ($result instanceof \Traversable) {
            $result = \iterator_to_array($result);
        }

        // Cache original action result
        $this->resultStore->set($this->getCurrentPath($input), $result);

        // After serializing
        $response = $this->resolved($field, $result);

        // Cache formatted resolver result
        $this->responseStore->set($this->getCurrentPath($input), $response);

        return $response;
    }

    /**
     * @param InputInterface $input
     */
    private function withParentValue(InputInterface $input): void
    {
        $parent = $this->getParentPath($input);

        $parentResult   = $this->resultStore->get($parent);
        $parentResponse = $this->responseStore->get($parent);

        $input->updateParent($parentResult, $parentResponse);
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function getParentPath(InputInterface $input): string
    {
        $parts = \explode(InputInterface::DEPTH_DELIMITER, $input->getPath());

        $parts = \array_slice($parts, 0, -1);

        return \implode(InputInterface::DEPTH_DELIMITER, $parts);
    }

    /**
     * @param FieldDefinition $field
     * @param Route $route
     * @param array $parameters
     * @return mixed
     */
    private function resolving(FieldDefinition $field, Route $route, array $parameters)
    {
        $event = $field->getParent()->getName() . ':' . $field->getName();

        return $this->events->dispatch(Event::RESOLVING . ':' . $event, [$field, $parameters]) ?? $parameters;
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function getCurrentPath(InputInterface $input): string
    {
        return $input->getPath();
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $data
     * @return mixed
     * @throws \RuntimeException
     */
    private function resolved(FieldDefinition $field, $data)
    {
        $this->verifyResult($field, $data);

        $event = $field->getTypeDefinition()->getName();

        return $this->events->dispatch(Event::RESOLVED . ':' . $event, [$field, $data]) ?? $data;
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $data
     * @throws \RuntimeException
     */
    private function verifyResult(FieldDefinition $field, $data): void
    {
        $valid = $field->isList() ? (\is_array($data) || \is_iterable($data)) : \is_scalar($data);

        if (! $valid) {
            $type = \mb_strtolower(\gettype($data));
            $args = [$field, $field->isList() ? 'iterable' : 'scalar', $type];

            throw new \RuntimeException(\vsprintf('Response type of %s must be %s, but %s given', $args));
        }
    }
}
