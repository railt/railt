<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Http\Connection as HttpConnection;
use Railt\Http\Pipeline\PipelineInterface;
use Railt\TypeSystem\Document\DocumentInterface;

/**
 * Class Connection
 */
class Connection extends HttpConnection
{
    /**
     * Connection constructor.
     *
     * @param ContainerInterface $container
     * @param DocumentInterface $document
     */
    public function __construct(ContainerInterface $container, DocumentInterface $document)
    {
        $pipeline = $this->createPipeline($container);

        parent::__construct($pipeline);
    }

    /**
     * @param ContainerInterface $app
     * @return PipelineInterface
     */
    private function createPipeline(ContainerInterface $app): PipelineInterface
    {
        try {
            return $app->make(PipelineInterface::class);
        } catch (ContainerResolutionException $e) {
            $message = $e->getMessage() . '. Make sure the HTTP extension is available';

            throw new \LogicException($message, $e->getCode());
        }
    }
}
