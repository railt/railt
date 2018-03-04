<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Runtime;

use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Runtime\Contracts\ClassLoader;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class RuntimeExtension
 */
class RuntimeExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $sdl
     */
    public function boot(CompilerInterface $sdl): void
    {
        $this->instance(DirectiveLoader::class, new DirectiveLoader());
        $this->alias(DirectiveLoader::class, ClassLoader::class);

        $sdl->compile(File::fromPathname(__DIR__ . '/resources/use.graphqls'));
    }
}
