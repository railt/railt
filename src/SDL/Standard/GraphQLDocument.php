<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Standard;

use Railt\Io\File;
use Railt\SDL\Base\BaseDocument;
use Railt\SDL\Contracts\Definitions\Definition;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Standard\Directives\Deprecation;
use Railt\SDL\Standard\Scalars\AnyType;
use Railt\SDL\Standard\Scalars\BooleanType;
use Railt\SDL\Standard\Scalars\DateTimeType;
use Railt\SDL\Standard\Scalars\FloatType;
use Railt\SDL\Standard\Scalars\IDType;
use Railt\SDL\Standard\Scalars\IntType;
use Railt\SDL\Standard\Scalars\StringType;

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
     * @var Dictionary
     */
    private $dictionary;

    /**
     * GraphQLDocument constructor.
     * @param Dictionary $dictionary
     * @param array|string[] $additionalTypes
     */
    public function __construct(Dictionary $dictionary, array $additionalTypes = [])
    {
        $this->file = File::fromSources('# Generated');
        $this->additionalTypes = $additionalTypes;

        $this->createStandardTypes();
        $this->dictionary = $dictionary;
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
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
            Deprecation::class,
        ];

        return \array_merge($standard, $this->additionalTypes);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::DOCUMENT_NAME;
    }
}
