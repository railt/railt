<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Interact;

use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Connection\ConnectionInterface;
use Railt\Io\File;

/**
 * Trait InteractWithConnection
 */
trait InteractWithConnection
{
    /**
     * @param string $schema
     * @return ConnectionInterface
     */
    protected function connect(string $schema): ConnectionInterface
    {
        return $this->app()->run(File::fromSources($schema));
    }

    /**
     * @return ApplicationInterface
     */
    abstract protected function app(): ApplicationInterface;
}
