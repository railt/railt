<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Kernel;

use Railt\Foundation\Extensions\BaseExtension;
use Railt\Foundation\Extensions\Status;
use Railt\Io\File;
use Railt\Kernel\Contracts\ClassLoader;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class KernelExtension
 */
class KernelExtension extends BaseExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Kernel';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Provides the ability to operate by referring to a PHP code.';
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '1.2';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @param CompilerInterface $compiler
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->bootServices();

        $compiler->compile(File::fromPathname(__DIR__ . '/../../resources/kernel/types.graphqls'));
    }

    /**
     * @return void
     */
    private function bootServices(): void
    {
        $this->instance(ClassLoader::class, new DirectiveLoader());
        $this->alias(ClassLoader::class, DirectiveLoader::class);
    }
}
