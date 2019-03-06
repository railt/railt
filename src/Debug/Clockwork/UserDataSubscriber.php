<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\Clockwork;

use Railt\Container\Container;
use Railt\Dumper\TypeDumper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserDataSubscriber
 */
abstract class UserDataSubscriber implements EventSubscriberInterface
{
    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    protected function getContainerTable(Container $container): array
    {
        $data = [];

        foreach ($this->extractContainer($container) as $key => $service) {
            $data[] = [
                'Service' => $key,
                'Value'   => TypeDumper::render($service),
                'Aliases' => \implode(', ', $this->getAliases($container, $key)),
            ];
        }

        return $data;
    }

    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    private function extractContainer(Container $container): array
    {
        $context = (new \ReflectionObject($container))->getParentClass();

        $property = $context->getProperty('registered');
        $property->setAccessible(true);

        return $property->getValue($container);
    }

    /**
     * @param Container $container
     * @param string $key
     * @return array
     * @throws \ReflectionException
     */
    private function getAliases(Container $container, string $key): array
    {
        $result = [];

        foreach ($this->extractAliases($container) as $alias => $service) {
            if ($service === $key) {
                $result[] = $alias;
            }
        }

        return $result;
    }

    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    private function extractAliases(Container $container): array
    {
        $context = (new \ReflectionObject($container))->getParentClass();

        $property = $context->getProperty('aliases');
        $property->setAccessible(true);

        return $property->getValue($container);
    }
}
