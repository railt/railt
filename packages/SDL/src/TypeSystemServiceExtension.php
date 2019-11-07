<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Foundation\Extension\Status;
use Railt\Foundation\Extension\Extension;
use Railt\Contracts\SDL\CompilerInterface;

/**
 * Class TypeSystemServiceExtension
 */
class TypeSystemServiceExtension extends Extension
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->register(CompilerInterface::class, fn () =>
            new Compiler(Compiler::SPEC_RAILT)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'GraphQL TypeSystem';
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): string
    {
        return $this->app->getVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Registers the GraphQL TypeSystem (SDL) parser and compiler';
    }
}
