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

/**
 * Class BasicResolver
 */
class DividedResolver extends BaseResolver
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

        $actionResult = $route->call($this->getParameters($input));

        return $this->response($input, $actionResult);
    }

    /**
     * @param InputInterface $input
     * @param null|Box $parent
     */
    protected function withParent(InputInterface $input, ?Box $parent): void
    {
        if ($parent) {
            $input->updateParent($parent->getValue(), $parent->toArray());
        }
    }
}
