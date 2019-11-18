<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation;

use PackageVersions\Versions;
use Railt\Container\Container;
use Railt\SDL\CompilerInterface;
use Railt\SDL\DocumentInterface;
use Psr\Container\ContainerInterface;
use Railt\Foundation\Http\GraphQLConnection;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\Foundation\Http\ConnectionInterface;
use Psr\Container\ContainerExceptionInterface;
use Railt\Foundation\Extension\ExtensionsTrait;
use Railt\Foundation\Console\ConsoleExecutorTrait;
use Railt\Foundation\Application\ConfigurationTrait;
use Railt\Foundation\Application\DefaultBindingsTrait;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Foundation\Extension\Exception\ExtensionException;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;

/**
 * Class Application
 */
class Application extends Container implements ApplicationInterface
{
    use ExtensionsTrait;
    use ConfigurationTrait;
    use DefaultBindingsTrait;
    use ConsoleExecutorTrait;

    /**
     * @var HttpKernel
     */
    protected HttpKernel $kernel;

    /**
     * Application constructor.
     *
     * @param ConfigRepositoryInterface|array $config
     * @param ContainerInterface|null $container
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    public function __construct($config = null, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->bootConfigurationTrait($config);
        $this->bootDefaultBindingsTrait();
        $this->bootExtendableTrait($this, $this->config);
        $this->bootConsoleExecutorTrait($this, $this->config);

        $this->kernel = new HttpKernel($this);
    }

    /**
     * @return ConnectionInterface
     * @throws ContainerExceptionInterface
     */
    public function test(): ConnectionInterface
    {
        return $this->connect(<<<'GraphQL'
            schema { 
                query: Query 
            }
            
            type Query {
                test: String
            }
        GraphQL
        );
    }

    /**
     * @param ReadableInterface|resource|string $schema
     * @return ConnectionInterface
     * @throws ContainerExceptionInterface
     */
    public function connect($schema): ConnectionInterface
    {
        $this->boot();

        return new GraphQLConnection($this, $this->kernel, $this->compile($schema));
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->extensions->boot();
    }

    /**
     * @param string|resource|ReadableInterface $schema
     * @return DocumentInterface
     * @throws ContainerExceptionInterface
     */
    protected function compile($schema): DocumentInterface
    {
        return $this->get(CompilerInterface::class)
            ->compile($schema)
            ;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        try {
            $chunks = \explode('@', Versions::getVersion('railt/railt'));
        } catch (\OutOfBoundsException $e) {
            $chunks = ['unknown'];
        }

        return \reset($chunks);
    }
}
