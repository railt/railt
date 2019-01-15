<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Container\ContainerInterface as Container;
use Railt\Foundation\Application;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\SDL\Compiler;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Storage\Storage;

/**
 * Class CompilerExtension
 */
class CompilerExtension extends Extension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'SDL';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'GraphQL SDL compiler integration';
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return ['railt/railt' => CacheExtension::class];
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->registerIfNotRegistered(CompilerInterface::class, function (Storage $cache, Container $app) {
            return new Compiler($cache);
        });
    }
}
