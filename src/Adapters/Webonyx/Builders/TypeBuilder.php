<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use Railt\Adapters\Webonyx\Registry;
use Railt\Events\Dispatcher;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class TypeBuilder
 */
abstract class TypeBuilder
{
    /**
     * @var TypeDefinition
     */
    protected $reflection;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * TypeBuilder constructor.
     * @param TypeDefinition $type
     * @param Registry $registry
     * @param Dispatcher $events
     */
    public function __construct(TypeDefinition $type, Registry $registry, Dispatcher $events)
    {
        $this->reflection = $type;
        $this->registry   = $registry;
        $this->events     = $events;
    }

    /**
     * @return mixed|Type
     */
    abstract public function build();

    /**
     * @return Registry
     */
    protected function getRegistry(): Registry
    {
        return $this->registry;
    }

    /**
     * @param string $service
     * @return mixed|object
     */
    protected function make(string $service)
    {
        return $this->registry->getContainer()->make($service);
    }

    /**
     * @param TypeDefinition $type
     * @return Type|Directive
     * @throws \InvalidArgumentException
     */
    protected function load(TypeDefinition $type)
    {
        return $this->registry->get($type);
    }
}
