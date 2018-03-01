<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Routing\Contracts\RegistryInterface;
use Railt\Routing\Contracts\RouterInterface;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class RouterServiceProvider
 */
class RouterExtension extends BaseExtension
{
    private const SCHEMA_FILE = __DIR__ . '/resources/graphql/route.graphqls';

    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->instance(RouterInterface::class, new Router($this->getContainer()));
        $this->instance(RegistryInterface::class, new Registry());

        $compiler->compile($this->getRouteDirective());
    }

    /**
     * @return Readable
     */
    private function getRouteDirective(): Readable
    {
        return File::fromPathname(self::SCHEMA_FILE);
    }
}
