<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Routing\Subscribers;

use Railt\Extension\Routing\DirectiveLoader;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DirectiveResolverSubscriber
 */
class DirectiveLoaderSubscriber implements EventSubscriberInterface
{
    /**
     * @var array|string[]
     */
    private $initialized = [];

    /**
     * @var DirectiveLoader
     */
    private $loader;

    /**
     * DirectiveLoaderSubscriber constructor.
     *
     * @param DirectiveLoader $loader
     */
    public function __construct(DirectiveLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FieldResolve::class => ['onFieldResolve', 100],
        ];
    }

    /**
     * @param FieldResolve $resolving
     * @throws \ReflectionException
     */
    public function onFieldResolve(FieldResolve $resolving): void
    {
        $id = $resolving->getFieldDefinition()->getUniqueId();

        if (! \in_array($id, $this->initialized, true)) {
            $this->loader->load($resolving->getFieldDefinition());

            $this->initialized[] = $id;
        }
    }
}
