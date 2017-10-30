<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Standard;

use Railt\Compiler\Filesystem\File;
use Railt\Compiler\Reflection\Base\BaseDocument;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Standard\Directives\Deprecation;
use Railt\Compiler\Reflection\Standard\Scalars\AnyType;
use Railt\Compiler\Reflection\Standard\Scalars\BooleanType;
use Railt\Compiler\Reflection\Standard\Scalars\DateTimeType;
use Railt\Compiler\Reflection\Standard\Scalars\FloatType;
use Railt\Compiler\Reflection\Standard\Scalars\IDType;
use Railt\Compiler\Reflection\Standard\Scalars\IntType;
use Railt\Compiler\Reflection\Standard\Scalars\StringType;

/**
 * This class contains a Document implementation for
 * the standard GraphQL library.
 */
class GraphQLDocument extends BaseDocument implements StandardType
{
    /**
     * The name of our document constant.
     */
    private const DOCUMENT_NAME = 'GraphQL Standard Library';

    /**
     * @var array
     */
    private $additionalTypes;

    /**
     * GraphQLDocument constructor.
     * @param CompilerInterface $compiler
     * @param array|string[] $additionalTypes
     */
    public function __construct(CompilerInterface $compiler, array $additionalTypes = [])
    {
        $this->additionalTypes = $additionalTypes;
        $this->file = File::fromSources('# Generated');
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
            /** @var Definition|mixed $instance */
            $instance = new $type($this);

            if ($instance instanceof TypeDefinition) {
                $this->types[$instance->getName()] = $instance;
            } else {
                $this->definitions[] = $instance;
            }
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
            // Scalars
            BooleanType::class,
            FloatType::class,
            IDType::class,
            IntType::class,
            StringType::class,
            AnyType::class,
            DateTimeType::class,

            // Directives
            Deprecation::class
        ];

        return \array_merge($standard, $this->additionalTypes);
    }
}
