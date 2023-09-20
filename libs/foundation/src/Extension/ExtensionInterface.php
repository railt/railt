<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\EventDispatcher\EventDispatcherInterface;

interface ExtensionInterface
{
    public function load(EventDispatcherInterface $dispatcher): void;

    public function unload(EventDispatcherInterface $dispatcher): void;
}
