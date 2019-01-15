<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Foundation\Application;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Storage\Drivers\ArrayStorage;
use Railt\Storage\Storage;

/**
 * Class CacheExtension
 */
class CacheExtension extends Extension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Cache';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Provides cache driver.';
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
     * @return void
     */
    public function register(): void
    {
        $this->registerIfNotRegistered(Storage::class, function () {
            return new ArrayStorage();
        });
    }
}
