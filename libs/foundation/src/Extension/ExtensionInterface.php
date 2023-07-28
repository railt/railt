<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface ExtensionInterface
{
    public function load(EventDispatcherInterface $dispatcher): void;
}
