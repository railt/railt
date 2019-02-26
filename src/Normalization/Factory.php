<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization;

use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class Normalizer
 */
class Factory implements NormalizerInterface
{
    /**
     * @var array|string[]
     */
    public const DEFAULT_NORMALIZERS = [
        TraversableNormalizer::class,
        ObjectNormalizer::class,
    ];

    /**
     * @var array|NormalizerInterface[]
     */
    private $normalizers = [];

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
        $this->container = $container;

        $this->bootNormalizers();
    }

    /**
     * @throws ContainerResolutionException
     */
    private function bootNormalizers(): void
    {
        foreach (self::DEFAULT_NORMALIZERS as $class) {
            $this->addNormalizer($this->container->make($class, [NormalizerInterface::class => $this]));
        }
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function addNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizers[] = $normalizer;
    }

    /**
     * @param mixed $result
     * @param int $options
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, int $options = 0)
    {
        foreach ($this->normalizers as $normalizer) {
            $result = $normalizer->normalize($result, $options);
        }

        return $result;
    }
}
