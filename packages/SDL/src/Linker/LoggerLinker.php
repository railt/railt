<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Linker;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Railt\Dumper\Facade;

/**
 * Class LoggerLinker
 */
class LoggerLinker implements LinkerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string
     */
    private const LOG_MESSAGE = 'Trying to load <%s#%d> named %s';

    /**
     * LoggerLinker constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param int $type
     * @param string|null $name
     * @return void
     */
    public function __invoke(int $type, ?string $name): void
    {
        $this->logger->info(\vsprintf(self::LOG_MESSAGE, [
            Type::toString($type),
            Facade::value($type),
            Facade::value($name),
        ]));
    }
}
