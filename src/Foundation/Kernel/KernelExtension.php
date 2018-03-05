<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Kernel;

use Railt\Foundation\Extensions\BaseExtension;
use Railt\Foundation\Kernel\Contracts\ClassLoader;
use Railt\Io\File;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class KernelExtension
 */
class KernelExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $sdl
     */
    public function boot(CompilerInterface $sdl): void
    {
        $this->instance(DirectiveLoader::class, new DirectiveLoader());
        $this->alias(DirectiveLoader::class, ClassLoader::class);

        $sdl->compile(File::fromPathname(__DIR__ . '/resources/types.graphqls'));
    }
}
