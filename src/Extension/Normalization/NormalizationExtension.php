<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Normalization;

use Railt\Container\Exception\ContainerInvocationException;
use Railt\Extension\Normalization\Context\Context;
use Railt\Foundation\Application;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;

/**
 * Class SerializationExtension
 */
class NormalizationExtension extends Extension
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'An extension that normalizes basic PHP data structures';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Normalization';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return ['railt/railt' => EventsExtension::class];
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * @return void
     * @throws ContainerInvocationException
     */
    public function register(): void
    {
        $this->registerIfNotRegistered(NormalizerInterface::class, function () {
            return new Factory($this->app());
        });

        $normalizer = $this->make(NormalizerInterface::class);

        $this->on(FieldResolve::class, static function (FieldResolve $event) use ($normalizer): void {
            if ($event->hasResult()) {
                $field = $event->getFieldDefinition();

                $result = $normalizer->normalize($event->getResult(), new Context($field));

                $event->withResult($result);
            }
        }, -100);
    }
}
