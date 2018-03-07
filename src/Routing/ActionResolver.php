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
use Railt\Routing\Route\Relation;

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
     * @param Route $route
     * @param InputInterface $input
     * @param array $parameters
     * @param $parent
     * @return mixed Response data
     * @throws \RuntimeException
     */
    public function call(Route $route, InputInterface $input, array $parameters, $parent)
    {
        if ($route->hasRelations()) {
            return $this->callSingularAction($route, $input, $parameters, $parent);
        }

        return $this->callDividedAction($route, $input, $parameters);
    }

    /**
     * @param Route $route
     * @param InputInterface $input
     * @param array $parameters
     * @param mixed $parent
     * @return mixed
     * @throws \RuntimeException
     */
    private function callSingularAction(Route $route, InputInterface $input, array $parameters, $parent)
    {
        $current = $this->isFirstInvocation($input)
            ? $this->callDividedAction($route, $input, $parameters)
            : $this->responseStore->get($this->getCurrentPath($input));

        $result = [];

        foreach ($route->getRelations() as $relation) {
            foreach ($current as $item) {
                if ($this->matched($relation, $parent, $item)) {
                    $result[] = $item;
                }
            }
        }

        if (\count($result) === 0 && ! $input->getFieldDefinition()->isNonNull()) {
            return null;
        }

        return $result;
    }

    /**
     * @param Relation $relation
     * @param array $parentItem
     * @param array $currentItem
     * @return bool
     */
    private function matched(Relation $relation, $parentItem, $currentItem): bool
    {
        if (! \is_array($parentItem) || ! \array_key_exists($relation->getParentFieldName(), $parentItem)) {
            return false;
        }

        if (! \is_array($currentItem) || ! \array_key_exists($relation->getChildFieldName(), $currentItem)) {
            return false;
        }

        return $parentItem[$relation->getParentFieldName()] === $currentItem[$relation->getChildFieldName()];
    }

    /**
     * @param InputInterface $input
     * @return bool
     */
    private function isFirstInvocation(InputInterface $input): bool
    {
        return ! $this->responseStore->has($this->getCurrentPath($input));
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
     * @param Route $route
     * @param InputInterface $input
     * @param array $parameters
     * @return mixed
     * @throws \RuntimeException
     */
    private function callDividedAction(Route $route, InputInterface $input, array $parameters)
    {
        $field = $input->getFieldDefinition();

        // Prepare input and parameters
        $parameters = $this->withParentValue($input, $parameters);

        // Update action parameters
        $parameters = $this->resolving($field, $parameters);

        // Call the action
        $result = $this->callAction($route, $parameters);

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
     * @param array $parameters
     * @return array
     */
    private function withParentValue(InputInterface $input, array $parameters): array
    {
        $parent = $this->getParentPath($input);

        $parentResult   = $this->resultStore->get($parent);
        $parentResponse = $this->responseStore->get($parent);

        $input->updateParent($parentResult, $parentResponse);

        return $parameters;
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
     * @param array $parameters
     * @return mixed
     */
    private function resolving(FieldDefinition $field, array $parameters)
    {
        $event = $field->getParent()->getName() . ':' . $field->getName();

        return $this->events->dispatch(Event::ACTION_RESOLVING . $event, [$field, $parameters]) ?? $parameters;
    }

    /**
     * @param Route $route
     * @param array $parameters
     * @return array|mixed
     */
    private function callAction(Route $route, array $parameters)
    {
        // Before serializing
        $result = $route->call($parameters);

        if ($result instanceof \Traversable) {
            $result = \iterator_to_array($result);
        }

        return $result;
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

        return $this->events->dispatch(Event::ACTION_RESOLVED . $event, [$field, $data]) ?? $data;
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
