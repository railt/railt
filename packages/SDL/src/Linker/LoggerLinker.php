<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\Dumper\Facade;
use Railt\SDL\Ast\Location;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Class LoggerLinker
 */
class LoggerLinker implements LinkerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string
     */
    private const LOG_MESSAGE = 'Trying to load <%s> named %s from %s:%d';

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
     * @param string|null $name
     * @param int $type
     * @param Location $from
     * @return void
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function __invoke(?string $name, int $type, Location $from): void
    {
        $this->logger->info(\vsprintf(self::LOG_MESSAGE, [
            Type::toString($type),
            Facade::value($name),
            $from->source instanceof FileInterface ? $from->source->getPathname() : 'source',
            $from->getStartLine(),
        ]));
    }
}
