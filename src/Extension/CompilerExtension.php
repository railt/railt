<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Parser\Factory;
use Railt\Extension\Status;
use Railt\Extension\Extension;
use Railt\TypeSystem\Compiler;
use Railt\TypeSystem\CompilerInterface;

/**
 * Class CompilerExtension
 */
class CompilerExtension extends Extension
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->register(Factory::class, fn () =>
            new Factory()
        );

        $this->app->register(CompilerInterface::class, fn (Factory $factory) =>
            new Compiler(Compiler::MODE_EXTENDED, $factory)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'GraphQL';
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
        return 'Registers the GraphQL parser and compiler';
    }
}
