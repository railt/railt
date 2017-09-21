<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard;

use Railt\Reflection\Contracts\Types\ScalarType;
use Railt\Reflection\Standard\Scalars\AnyType;
use Railt\Reflection\Standard\Scalars\BooleanType;
use Railt\Reflection\Standard\Scalars\DateTimeType;
use Railt\Reflection\Standard\Scalars\FloatType;
use Railt\Reflection\Standard\Scalars\IDType;
use Railt\Reflection\Standard\Scalars\IntType;
use Railt\Reflection\Standard\Scalars\StringType;

/**
 * This class contains a Document implementation for
 * the standard GraphQL library.
 */
class GraphQLDocument extends BaseDocument
{
    /**
     * Adding an Any type implementation
     */
    public const RFC325 = AnyType::class;

    /**
     * Adding an DateTime type implementation
     */
    public const RFC315 = DateTimeType::class;

    /**
     * Non-standard features
     */
    public const EXPERIMENTAL = [
        self::RFC315,
        self::RFC325
    ];

    /**
     * The name of our document constant.
     */
    private const DOCUMENT_NAME = 'GraphQL Standard Library';

    /**
     * @var array
     */
    private $experimentalFeatures = [];

    /**
     * GraphQLDocument constructor.
     * @param array|null $experimental
     */
    public function __construct(array $experimental = null)
    {
        $this->experimentalFeatures = $experimental ?? static::EXPERIMENTAL;
        $this->createStandardTypes();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::DOCUMENT_NAME;
    }

    /**
     * Creation and registration of types.
     *
     * @return void
     */
    private function createStandardTypes(): void
    {
        foreach ($this->getStandardTypes() as $type) {
            /** @var ScalarType|mixed $instance */
            $instance = new $type($this);

            $this->types[$instance->getName()] = $instance;
        }
    }

    /**
     * Returns should return a list of all predefined GraphQL types.
     *
     * @return array|StandardType[]
     */
    private function getStandardTypes(): array
    {
        $standard = [
            BooleanType::class,
            FloatType::class,
            IDType::class,
            IntType::class,
            StringType::class,
        ];

        return \array_merge($standard, $this->experimentalFeatures);
    }
}
