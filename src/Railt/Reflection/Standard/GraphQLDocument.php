<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard;

use Railt\Reflection\Base\BaseDocument;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Types\ScalarType;
use Railt\Reflection\Standard\Directives\Deprecation;
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
class GraphQLDocument extends BaseDocument implements StandardType
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
     * Adding an directive:
     * <code>
     *      @deprecated(reason: String = "No longer supported") on FIELD_DEFINITION | ENUM_VALUE
     * </code>
     */
    public const RFC384 = Deprecation::class;

    /**
     * Non-standard features
     */
    public const EXPERIMENTAL = [
        self::RFC315,
        self::RFC325,
        self::RFC384
    ];

    /**
     * The name of our document constant.
     */
    private const DOCUMENT_NAME = 'GraphQL Standard Library';

    /**
     * @var array
     */
    private $experimentalFeatures;

    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * GraphQLDocument constructor.
     * @param CompilerInterface $compiler
     * @param array|null $experimental
     */
    public function __construct(CompilerInterface $compiler, array $experimental = null)
    {
        $this->compiler = $compiler;
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

            // Eager registering
            $this->compiler->register($instance);
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
