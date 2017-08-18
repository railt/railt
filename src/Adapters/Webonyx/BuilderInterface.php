<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Adapters\Webonyx;

use Railgun\Routing\Router;
use Railgun\Support\Dispatcher;
use Railgun\Reflection\Abstraction\DefinitionInterface;
use Railgun\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface BuilderInterface
 * @package Railgun\Adapters\Webonyx
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     * @param Dispatcher $events
     * @param Router $router
     * @param Loader $loader
     * @param DefinitionInterface|TypeInterface $target
     */
    public function __construct(Dispatcher $events, Router $router, Loader $loader, $target);

    /**
     * @return Loader
     */
    public function getLoader(): Loader;

    /**
     * @return DefinitionInterface|TypeInterface
     */
    public function getTarget();

    /**
     * @return mixed
     */
    public function build();
}
