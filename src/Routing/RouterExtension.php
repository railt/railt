<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Events\Dispatcher;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Routing\Contracts\RegistryInterface;
use Railt\Routing\Contracts\RouterInterface;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class RouterServiceProvider
 */
class RouterExtension extends BaseExtension
{
    /**
     * @var string
     */
    private const SCHEMA_ROUTE_FILE = __DIR__ . '/resources/graphql/route.graphqls';

    /**
     * @var string
     */
    private const SCHEMA_USE_FILE = __DIR__ . '/resources/graphql/use.graphqls';

    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->instance(RouterInterface::class, new Router($this->getContainer()));
        $this->instance(RegistryInterface::class, new Registry());

        foreach ($this->getSchemaFiles() as $schema) {
            $compiler->compile(File::fromPathname($schema));
        }

        $this->bootFieldResolver($this->make(RouterInterface::class), $this->make(Dispatcher::class));
    }

    /**
     * @param RouterInterface $router
     * @param Dispatcher $events
     */
    private function bootFieldResolver(RouterInterface $router, Dispatcher $events): void
    {
        $resolver = new FieldResolver($this->getContainer(), $router);

        $events->listen('resolver:*', function(string $event, array $params) use ($resolver) {
            return $resolver->handle(...$params);
        });
    }

    /**
     * @return iterable|string[]
     */
    private function getSchemaFiles(): iterable
    {
        yield self::SCHEMA_USE_FILE;
        yield self::SCHEMA_ROUTE_FILE;
    }
}
