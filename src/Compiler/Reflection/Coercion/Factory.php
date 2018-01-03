<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Coercion;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class Factory
 */
class Factory implements TypeCoercion
{
    /**
     * @var array|BaseTypeCoercion[]|string[]
     */
    private const DEFAULT_TRANSFORMERS = [
        ArgumentCoercion::class,
        PassedArgumentsCoercion::class,
        DeprecationCoercion::class,
    ];

    /**
     * @var array|BaseTypeCoercion[]
     */
    private $transformers = [];

    /**
     * Factory constructor.
     */
    public function __construct()
    {
        $this->add(...self::DEFAULT_TRANSFORMERS);
    }

    /**
     * @param string[]|BaseTypeCoercion[] ...$transformers
     * @return Factory
     */
    public function add(string ...$transformers): self
    {
        foreach ($transformers as $transformer) {
            $this->transformers[] = new $transformer();
        }

        return $this;
    }

    /**
     * @param TypeDefinition $type
     * @return TypeDefinition
     */
    public function apply(TypeDefinition $type): TypeDefinition
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->match($type)) {
                $transformer->apply($type);
            }
        }

        return $type;
    }
}
