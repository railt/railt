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
use Phplrt\Contracts\Source\ReadableInterface;
use Psr\Container\ContainerInterface;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Container\Container;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Foundation\Application\ConfigurationTrait;
use Railt\Foundation\Application\DefaultBindingsTrait;
use Railt\Foundation\Console\ConsoleExecutorTrait;
use Railt\Foundation\Extension\Exception\ExtensionException;
use Railt\Foundation\Extension\ExtensionsTrait;
use Railt\Foundation\Http\ConnectionInterface;
use Railt\Foundation\Http\GraphQLConnection;
use Railt\Http\HttpKernelInterface;
use Railt\TypeSystem\CompilerInterface;
use Railt\TypeSystem\Document\DocumentInterface;

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
    }

    /**
     * @param ReadableInterface|resource|string $schema
     * @return ConnectionInterface
     * @throws ContainerInvocationException
     */
    public function connect($schema): ConnectionInterface
    {
        $this->boot();

        $kernel = $this->make(HttpKernelInterface::class);

        return new GraphQLConnection($this, $kernel, $this->compile($schema));
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
     * @throws ContainerInvocationException
     */
    protected function compile($schema): DocumentInterface
    {
        if (! $this->has(CompilerInterface::class)) {
            $message = 'Can not run application: GraphQL type system compiler not defined';
            throw new \LogicException($message);
        }

        return $this->make(CompilerInterface::class)
            ->compile($schema);
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
