<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Serialize;

use Railt\Events\Dispatcher;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class SerializeExtension
 */
class SerializeExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $compiler
     */
    public function boot(CompilerInterface $compiler): void
    {
        $compiler->compile(File::fromPathname(__DIR__ . '/resources/serializer.graphqls'));

        $this->call(\Closure::fromCallable([$this, 'bootFieldResolver']));
    }

    /**
     * @param Dispatcher $events
     */
    private function bootFieldResolver(Dispatcher $events): void
    {

    }
}
