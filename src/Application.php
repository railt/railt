<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Ast\Node;
use PackageVersions\Versions;
use Railt\Container\Container;
use Psr\Container\ContainerInterface;
use Railt\TypeSystem\CompilerInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\TypeSystem\Document\DocumentInterface;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Foundation\Console\ConsoleExecutorTrait;
use Railt\Foundation\Application\DefaultExtensionsTrait;
use Railt\Foundation\Extension\Exception\ExtensionException;
use Railt\Config\MutableRepository as ConfigRepository;
use Railt\Foundation\Application\DefaultBindingsTrait;
use Railt\Container\Exception\ContainerInvocationException;
use Symfony\Component\Console\Application as CliApplication;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Foundation\Extension\ConfigurationRepository as ExtensionRepository;
use Railt\Foundation\Extension\RepositoryInterface as ExtensionRepositoryInterface;

/**
 * Class Application
 */
class Application extends Container implements ApplicationInterface
{
    use DefaultBindingsTrait;
    use ConsoleExecutorTrait;
    use DefaultExtensionsTrait;

    /**
     * @var ConfigRepositoryInterface
     */
    protected ConfigRepositoryInterface $config;

    /**
     * @var ExtensionRepositoryInterface
     */
    protected ExtensionRepositoryInterface $extensions;

    /**
     * Application constructor.
     *
     * @param ConfigRepositoryInterface $config
     * @param ContainerInterface|null $container
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    public function __construct(ConfigRepositoryInterface $config = null, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->bootConfig($config);

        $this->extensions = new ExtensionRepository($this, $this->config);

        $this->bootConsoleExecutorTrait($this, $this->config);
        $this->bootDefaultBindingsTrait();
        $this->bootDefaultExtensionsTrait();
    }

    /**
     * @param ConfigRepositoryInterface|null $config
     * @return void
     */
    private function bootConfig(ConfigRepositoryInterface $config = null): void
    {
        $result = new ConfigRepository();

        if ($config !== null) {
            $result->merge($config);
        }

        $this->config = $result;
    }

    /**
     * @param string $extension
     * @return void
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    public function extend(string $extension): void
    {
        \assert(\is_subclass_of($extension, ExtensionInterface::class));

        $this->extensions->add($extension);
    }

    /**
     * @param string|resource|ReadableInterface $schema
     * @return DocumentInterface
     * @throws ContainerInvocationException
     */
    public function connect($schema)
    {
        $this->boot();

        return $this->compile($schema);

        // TODO
    }

    /**
     * @return void
     * @throws ContainerInvocationException
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
    public function compile($schema): DocumentInterface
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
