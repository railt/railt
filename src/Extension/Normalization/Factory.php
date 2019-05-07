<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Normalization;

use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Extension\Normalization\Context\ContextInterface;

/**
 * Class Normalizer
 */
class Factory implements NormalizerInterface
{
    /**
     * @var array|string[]
     */
    public const DEFAULT_NORMALIZERS = [
        Normalizer\IterableAsListNormalizer::class,
        Normalizer\IterableAsCompositeNormalizer::class,
        Normalizer\ObjectAsCompositeNormalizer::class,
        Normalizer\ObjectAsScalarNormalizer::class,
    ];

    /**
     * @var \SplDoublyLinkedList|NormalizerInterface[]
     */
    private $normalizers;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Factory constructor.
     *
     * @param ContainerInterface $container
     * @throws ContainerResolutionException
     */
    public function __construct(ContainerInterface $container)
    {
        $this->normalizers = new \SplDoublyLinkedList();
        $this->container = $container;

        $this->bootNormalizers();
    }

    /**
     * @throws ContainerResolutionException
     */
    private function bootNormalizers(): void
    {
        foreach (self::DEFAULT_NORMALIZERS as $class) {
            $this->append($this->container->make($class, [NormalizerInterface::class => $this]));
        }
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function append(NormalizerInterface $normalizer): void
    {
        $this->normalizers->push($normalizer);
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function prepend(NormalizerInterface $normalizer): void
    {
        $this->normalizers->unshift($normalizer);
    }

    /**
     * @param mixed $result
     * @param ContextInterface $context
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, ContextInterface $context)
    {
        foreach ($this->normalizers as $normalizer) {
            $result = $normalizer->normalize($result, $context);
        }

        return $result;
    }
}
