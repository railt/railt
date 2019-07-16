<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug\Clockwork;

use Clockwork\Clockwork;
use Clockwork\Request\UserData;
use Illuminate\Support\Arr;
use Railt\Container\Container;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;
use Railt\Dumper\TypeDumper;
use Railt\Foundation\Config\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtConfigurationSubscriber
 */
class RailtConfigurationSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @var UserData
     */
    private $data;

    /**
     * FieldResolveSubscriber constructor.
     *
     * @param Clockwork $clockwork
     * @param Container $app
     */
    public function __construct(Clockwork $clockwork, Container $app)
    {
        $this->app = $app;
        $this->data = $clockwork
            ->userData('railt-config')
            ->title('Configuration');

        $this->shareConfigs();
    }

    /**
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function shareConfigs(): void
    {
        /** @var RepositoryInterface $config */
        $config = $this->app->make(RepositoryInterface::class);

        $configs = [];

        foreach (Arr::dot($config->all()) as $key => $value) {
            $value = \is_scalar($value) ? $value : TypeDumper::render($value);

            $configs[] = ['Name' => $key, 'Value' => $value];
        }

        $this->data->table('', $configs);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
