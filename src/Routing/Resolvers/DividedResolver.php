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
use Railt\Routing\Store\ObjectBox;

/**
 * Class DividedResolver
 */
class DividedResolver extends BaseResolver
{
    /**
     * @param InputInterface $input
     * @param Route $route
     * @param null|ObjectBox $parent
     * @return mixed
     */
    public function call(Route $route, InputInterface $input, ?ObjectBox $parent)
    {
        $this->withParent($route, $input, $parent);

        $actionResult = $route->call($this->getParameters($input));

        return $this->response($route, $input, $actionResult);
    }

    /**
     * @param Route $route
     * @param InputInterface $input
     * @param null|ObjectBox $parent
     */
    protected function withParent(Route $route, InputInterface $input, ?ObjectBox $parent): void
    {
        if ($parent) {
            $input->updateParent($parent->getValue(), $parent->getResponse());
        }
    }
}
