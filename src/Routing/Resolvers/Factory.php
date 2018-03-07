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
use Railt\Routing\Store\Box;
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
     * @param InputInterface $input
     * @param Route $route
     * @param null|Box $parent
     * @return mixed
     */
    public function call(InputInterface $input, Route $route, ?Box $parent)
    {
        if ($route->hasRelations()) {
            return $this->singular->call($input, $route, $parent);
        }

        return $this->divided->call($input, $route, $parent);
    }
}
