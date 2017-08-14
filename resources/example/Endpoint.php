<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Example;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Serafim\Railgun\Compiler\File;
use Monolog\Handler\StreamHandler;
use Serafim\Railgun\Endpoint as BaseEndpoint;

/**
 * Class Endpoint
 * @package Railgun\Example
 */
class Endpoint extends BaseEndpoint
{
    /**
     * Endpoint constructor.
     * @param File $schema
     * @throws \Exception
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     */
    public function __construct(File $schema)
    {
        parent::__construct($schema);

        $this->withLogger($this->createLogger());
        $this->debugMode();
    }

    /**
     * @return LoggerInterface
     * @throws \Exception
     */
    private function createLogger(): LoggerInterface
    {
        $streams = [
            new StreamHandler('php://stdout')
        ];

        return new Logger('Railgun', $streams);
    }
}
