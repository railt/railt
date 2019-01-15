<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Normalization;

use Railt\Container\ContainerInterface;
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
     * Normalizer constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->bootNormalizers();
    }

    /**
     * @return void
     */
    private function bootNormalizers(): void
    {
        foreach (self::DEFAULT_NORMALIZERS as $class) {
            $this->addNormalizer($this->container->make($class));
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
     * @param FieldDefinition $field
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, FieldDefinition $field)
    {
        foreach ($this->normalizers as $normalizer) {
            $result = $normalizer->normalize($result, $field);
        }

        return $result;
    }
}
