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
use Railt\Dumper\TypeDumper;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Standard\StandardType;
use Railt\Foundation\Config\RepositoryInterface;
use Railt\Foundation\Event\Http\ResponseProceed;

/**
 * Class ApplicationUserDataSubscriber
 */
class ApplicationUserDataSubscriber extends UserDataSubscriber
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
     * @throws \ReflectionException
     */
    public function __construct(Clockwork $clockwork, Container $app)
    {
        $this->app = $app;
        $this->data = $clockwork->userData('railt')->title('Railt');

        $this->shareContainer();
        $this->shareConfigs();
    }

    /**
     * @throws \ReflectionException
     */
    private function shareContainer(): void
    {
        $this->data->table('Application Container', $this->getContainerTable($this->app));
    }

    /**
     * @throws \Railt\Container\Exception\ContainerInvocationException
     * @throws \Railt\Container\Exception\ContainerResolutionException
     * @throws \Railt\Container\Exception\ParameterResolutionException
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

        $this->data->table('Config', $configs);
    }

    private function shareGraphQLTypes(): void
    {
        $dictionary = $this->app->make(Dictionary::class);

        $types = [];

        foreach ($dictionary->all() as $type) {
            $std = $type instanceof StandardType;
            $isFile = $type->getDocument()->getFile()->isFile();

            $types[] = [
                'Type'     => ($std ? '(builtin) ' : '') . (string)$type,
                'Document' => $isFile ? $type->getDocument()->getFile()->getPathname() : 'runtime',
            ];
        }

        $this->data->table('GraphQL SDL', $types);
    }

    /**
     * @param ResponseProceed $response
     */
    public function onResponse(ResponseProceed $response): void
    {
        $this->shareGraphQLTypes();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseProceed::class => ['onResponse', -100],
        ];
    }
}
