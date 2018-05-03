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
use Railt\Reflection\Contracts\Definitions\EnumDefinition;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Routing\Route;
use Railt\Routing\Store\ObjectBox;
use Railt\Routing\Store\Store;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Factory
 */
class Factory implements Resolver
{
    /**
     * @var DividedResolver
     */
    private $divided;

    /**
     * @var SingularResolver
     */
    private $singular;

    /**
     * Factory constructor.
     * @param EventDispatcherInterface $events
     */
    public function __construct(EventDispatcherInterface $events)
    {
        $store = new Store();

        $this->divided  = new DividedResolver($events, $store);
        $this->singular = new SingularResolver($events, $store);
    }

    /**
     * @param Route $route
     * @param InputInterface $input
     * @param null|ObjectBox $parent
     * @return mixed
     */
    public function call(Route $route, InputInterface $input, ?ObjectBox $parent)
    {
        if ($route->hasRelations() && $this->validateRelation($input)) {
            return $this->singular->call($route, $input, $parent);
        }

        return $this->divided->call($route, $input, $parent);
    }

    /**
     * @param InputInterface $input
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function validateRelation(InputInterface $input): bool
    {
        $type = $input->getFieldDefinition()->getTypeDefinition();

        if ($type instanceof EnumDefinition || $type instanceof ScalarDefinition) {
            $error = 'Specifying a relation for a field %s that returns a scalar value is invalid';
            throw new \InvalidArgumentException(\sprintf($error, $input->getFieldDefinition()));
        }

        return true;
    }
}
