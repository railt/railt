<?php

declare(strict_types=1);

namespace Railt\Extension\DefaultValue;

use Railt\EventDispatcher\EventDispatcherInterface;
use Railt\Foundation\Extension\ExtensionInterface;

/**
 * @template-implements ExtensionInterface<DefaultValueContext>
 */
final class DefaultValueExtension implements ExtensionInterface
{
    public function load(EventDispatcherInterface $dispatcher): object
    {
        return new DefaultValueContext($dispatcher);
    }

    public function unload(object $context): void
    {
        assert($context instanceof DefaultValueContext);

        $context->dispose();
    }
}
