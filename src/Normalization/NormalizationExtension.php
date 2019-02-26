<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization;

use Railt\Container\Exception\ContainerInvocationException;
use Railt\Foundation\Application;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\SDL\Contracts\Definitions\EnumDefinition;
use Railt\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

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
     * @throws ContainerInvocationException
     */
    public function register(): void
    {
        $this->registerIfNotRegistered(NormalizerInterface::class, function () {
            return new Factory($this->app());
        });
    }

    /**
     * @param NormalizerInterface $normalizer
     * @return void
     * @throws \Railt\Container\Exception\ContainerResolutionException
     */
    public function boot(NormalizerInterface $normalizer): void
    {
        $this->on(FieldResolve::class, function (FieldResolve $event) use ($normalizer): void {
            if ($event->hasResult()) {
                $field = $event->getFieldDefinition();

                $result = $normalizer->normalize($event->getResult(), $this->fieldToOptions($field));

                $event->withResult($result);
            }
        }, -100);
    }

    /**
     * @param FieldDefinition $field
     * @return int
     */
    private function fieldToOptions(FieldDefinition $field): int
    {
        $result = 0;

        if ($field->isList()) {
            $result |= NormalizerInterface::LIST;
        }

        if ($field->isNonNull()) {
            $result |= NormalizerInterface::NON_NULL;
        }

        if ($field->isListOfNonNulls()) {
            $result |= NormalizerInterface::LIST_OF_NON_NULLS;
        }

        $type = $field->getTypeDefinition();

        if ($type instanceof ScalarDefinition || $type instanceof EnumDefinition) {
            $result |= NormalizerInterface::TYPE_SCALAR;
        }

        return $result;
    }
}
