<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Resolvers;

use Railt\Http\InputInterface;
use Railt\Routing\Route;
use Railt\Routing\Route\Relation;
use Railt\Routing\Store\Box;

/**
 * Class SingularResolver
 */
class SingularResolver extends BaseResolver
{
    /**
     * @param InputInterface $input
     * @param Route $route
     * @param null|Box $parent
     * @return mixed
     */
    public function call(InputInterface $input, Route $route, ?Box $parent)
    {
        $this->withParent($input, $parent);

        if (! $parent || $this->isFirstInvocation($input)) {
            $actionResult = $route->call($this->getParameters($input));

            $this->response($input, $actionResult);
        }

        return $this->extract($input, $route, $parent);
    }

    /**
     * @param InputInterface $input
     * @param Route $route
     * @param Box $parent
     * @return array
     */
    private function extract(InputInterface $input, Route $route, Box $parent): array
    {
        $result = [];

        /** @var Box $current */
        foreach ($this->store->get($input) as $current) {
            foreach ($route->getRelations() as $relation) {
                if ($this->matched($relation, $parent, $current)) {
                    $result[] = $current;
                }
            }
        }

        return $result;
    }

    /**
     * @param InputInterface $input
     * @param null|Box $parent
     */
    protected function withParent(InputInterface $input, ?Box $parent): void
    {
        if ($parent) {
            $box = Box::restruct($this->store->getParent($input));

            $input->updateParent($box->getValue(), $box->toArray());
        }
    }

    /**
     * @param Relation $relation
     * @param Box $parent
     * @param Box $current
     * @return bool
     */
    private function matched(Relation $relation, Box $parent, Box $current): bool
    {
        if (! $parent->offsetExists($relation->getParentFieldName())) {
            return false;
        }

        if (! $current->offsetExists($relation->getChildFieldName())) {
            return false;
        }

        return $parent[$relation->getParentFieldName()] === $current[$relation->getChildFieldName()];
    }

    /**
     * @param InputInterface $input
     * @return bool
     */
    private function isFirstInvocation(InputInterface $input): bool
    {
        return ! $this->store->has($input);
    }
}
